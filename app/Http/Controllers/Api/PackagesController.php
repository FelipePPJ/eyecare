<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Package;
use App\Models\PackagesVsExam;

/**
 * Classe para consumo via API
 */
class PackagesController extends Controller
{
    /**
     * Busca de relação de pacotes de exames cadastrados e seus respectivos relacionamento com exames e grupos
     * @param Void
     * @return Json
     */
    public function index()
    {
        // Carga de exames com relacionamento de group associado
        $data = Package::with(['exams.group'])->get();

        return response()->json($data);
    }

    /**
     * Realiza validação e cadastro de um novo pacote de exames
     * @param Request $request
     * @return Json
     */
    public function store(Request $request)
    {
        try {
            /**
             * Validação principal dos campos submetidos
             */
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'observations' => 'nullable|string',

                'exams' => 'required|array|min:1',
                'exams.*.exam_id' => 'required|exists:exams,id',
                'exams.*.group_id' => 'required|exists:groups,id',
                'exams.*.laterality' => ['nullable', 'in:' . implode(",", config('application.laterality'))],
                'exams.*.comment' => 'nullable|string',
            ], [
                'name.required' => __('system.packages.create.validation.name'),
                'exams.required' => __('system.packages.create.validation.exams_required'),
                'exams.*.exam_id.required' => __('system.packages.create.validation.exams_required'),
                'exams.*.exam_id.exists' => __('system.packages.create.validation.exam_exists'),
                'exams.*.group_id.required' => __('system.packages.create.validation.group_id_required'),
                'exams.*.group_id.exists' => __('system.packages.create.validation.group_id_exists'),
            ]);

            /**
             * Inicia transação (garante rollback se der erro em algum ponto)
             */
            DB::beginTransaction();

            /**
             * Cria novo pacote
             */
            $package = Package::create([
                'name' => $validated['name'],
                'observations' => $validated['observations'] ?? null,
            ]);

            /**
             * Criação dos registros de relacionamento de exames
             */
            foreach ($validated['exams'] as $examData) {
                $package->exams()->attach($examData['exam_id'], [
                    'group_id' => $examData['group_id'],
                    'laterality' => $examData['laterality'] ?? null,
                    'comment' => $examData['comment'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            /**
             * Processa gravação
             */
            DB::commit();

            // Retorna o pacote criado com exames e grupos carregados
            return response()->json([
                'message' => __('system.packages.create.success'),
                'package' => $package->load(['exams.group']),
            ], 201);
        } catch (ValidationException $e) {
            // Retorno de erros de validação
            DB::rollBack(); // Caso alguma falha, retrocede procedimentos no banco

            return response()->json([
                'message' => __('system.packages.create.invalid'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Demais erros
            DB::rollBack(); // Caso alguma falha, retrocede procedimentos no banco

            return response()->json([
                'message' => __('system.packages.create.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualiza um pacote de exames existente
     * @param Request $request
     * @param int $id
     * @return Json
     */
    public function update(Request $request, $id)
    {
        try {
            // Busca o pacote
            $package = Package::findOrFail($id);

            // Validação principal
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'observations' => 'nullable|string',
                'exams' => 'required|array|min:1',
                'exams.*.exam_id' => 'required|exists:exams,id',
                'exams.*.group_id' => 'required|exists:groups,id',
                'exams.*.laterality' => ['nullable', 'in:' . implode(',', config('application.laterality'))],
                'exams.*.comment' => 'nullable|string',
            ], [
                'name.required' => __('system.packages.update.validation.name'),
                'exams.required' => __('system.packages.update.validation.exams_required'),
                'exams.*.exam_id.required' => __('system.packages.update.validation.exams_required'),
                'exams.*.exam_id.exists' => __('system.packages.update.validation.exam_exists'),
                'exams.*.group_id.required' => __('system.packages.update.validation.group_id_required'),
                'exams.*.group_id.exists' => __('system.packages.update.validation.group_id_exists'),
            ]);

            /**
             * Inicia transação (garante rollback se der erro em algum ponto)
             */
            DB::beginTransaction();

            // Atualiza informações sobre pacote
            $package->update([
                'name' => $validated['name'],
                'observations' => $validated['observations'] ?? null,
            ]);

            // Recupera os exames atuais do pacote
            $currentExams = PackagesVsExam::where('package_id', $package->id)->withTrashed()->get()->keyBy('exam_id');

            // Relação de Ids recebidos
            $submittedExams = collect($validated['exams'])->pluck('exam_id')->all();

            // Loop para atualizar/adicionar exames
            foreach ($validated['exams'] as $examData) {
                if (isset($currentExams[$examData['exam_id']])) {
                    // Atualiza existente
                    $currentExams[$examData['exam_id']]->update([
                        'group_id' => $examData['group_id'],
                        'laterality' => $examData['laterality'] ?? null,
                        'comment' => $examData['comment'] ?? null,
                        'deleted_at' => null
                    ]);
                } else {
                    // Cria novo
                    PackagesVsExam::create([
                        'package_id' => $package->id,
                        'exam_id' => $examData['exam_id'],
                        'group_id' => $examData['group_id'],
                        'laterality' => $examData['laterality'] ?? null,
                        'comment' => $examData['comment'] ?? null,
                    ]);
                }
            }

            // Soft delete para exames removidos
            foreach ($currentExams as $exam_id => $packageExam) {
                if (!in_array($exam_id, $submittedExams)) {
                    $packageExam->delete();
                }
            }

            /**
             * Processa gravação
             */
            DB::commit();

            // Retorna o pacote atualizado com os exames
            return response()->json([
                'message' => __('system.packages.update.success'),
                'package' => $package->load('packages.exams', 'packages.groups'),
            ], 200);
        } catch (ValidationException $e) {
            // Erros de validação
            DB::rollBack(); // Caso alguma falha, retrocede procedimentos no banco

            return response()->json([
                'message' => __('system.packages.update.invalid'),
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Erros vindos da model
            DB::rollBack(); // Caso alguma falha, retrocede procedimentos no banco

            return response()->json([
                'message' => __('system.packages.update.notfound'),
            ], 404);
        } catch (\Exception $e) {
            // Demais erros
            DB::rollBack(); // Caso alguma falha, retrocede procedimentos no banco

            return response()->json([
                'message' => __('system.packages.update.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
