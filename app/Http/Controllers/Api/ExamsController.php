<?php

namespace App\Http\Controllers\Api;

use App\Models\Exam;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Classe para consumo via API
 */
class ExamsController extends Controller
{
    /**
     * Realiza busca e contagem de registros no banco. Contagem de Exames e Pacotes
     * @param Void
     * @return Json
     */
    public function index()
    {
        // Carga de exames com relacionamento de group associado
        $data = Exam::with(['group'])->get();

        return response()->json($data);
    }

    /**
     * Realiza validação e cadastro de um novo exame
     * @param Request $request enviado via API/form
     * @return Json
     */
    public function store(Request $request)
    {
        try {

            /**
             * Validação de campos recebidos de exame
             */
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'group_id' => 'required|exists:groups,id',
                'laterality' => ['nullable', 'in:' . implode(",", config('application.laterality'))],
                'comment' => 'nullable|string',
            ], [
                'name.required' => __('system.exams.create.validation.name'),
                'group_id.required' => __('system.exams.create.validation.group_id_required'),
                'group_id.exists' => __('system.exams.create.validation.group_id_exists'),
                'laterality' => __('system.exams.create.validation.laterality'),
            ]);

            /**
             * Inserção de registro na base
             */
            $exam = Exam::create($validated);

            //Retorna o registro recém-criado, já com o relacionamento
            return response()->json([
                'message' => __('system.exams.create.success'),
                'exam' => $exam->load('group'),
            ], 201);
        } catch (ValidationException $e) {

            //Retorna de forma amigável via JSON
            return response()->json([
                'message' => __('system.exams.create.invalid'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            //Retorna outros possíveis erros
            return response()->json([
                'message' => __('system.exams.create.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualiza um exame existente
     * @param Request $request enviado via API/form
     * @param int $id enviado via API/form
     * @return Json
     */
    public function update(Request $request, $id)
    {
        try {
            // Busca o exame no banco
            $exam = Exam::findOrFail($id);

            /**
             * Validação dos campos recebidos
             */
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'group_id' => 'required|exists:groups,id',
                'laterality' => ['nullable', 'in:' . implode(",", config('application.laterality'))],
                'comment' => 'nullable|string',
            ], [
                'name.required' => 'O nome do exame é obrigatório.',
                'group_id.required' => 'O grupo é obrigatório.',
                'group_id.exists' => 'O grupo informado não existe.',
                'laterality.required' => 'Uma lateralidade deve ser escolhida.',
            ]);

            /**
             * Atualiza o registro no banco
             */
            $exam->update($validated);

            // Retorna o registro atualizado com o relacionamento
            return response()->json([
                'message' => __('system.exams.update.success'),
                'exam' => $exam->load('group'),
            ], 200);
        } catch (ValidationException $e) {
            // Falha na validação
            return response()->json([
                'message' => __('system.exams.update.invalid'),
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Falha na carga via model
            return response()->json([
                'message' => __('system.exams.update.notfound'),
            ], 404);
        } catch (\Exception $e) {
            // Outros erros
            return response()->json([
                'message' => __('system.exams.update.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
