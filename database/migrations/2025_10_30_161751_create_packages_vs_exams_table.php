<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration base para gravação de cruzamento entre exames e pacotes.
     * Todo pacote cadastrado terá exames associados. Esta tabela gera o relacionamento 1-n entre pacote e exames.
     * Assim, para cada pacote cadastrado, é possível localizar os exames associados a ele.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages_vs_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id')->index();
            $table->unsignedBigInteger('exam_id')->index();

            // Possibilidade de customização de variações do cadastro base do exame
            $table->unsignedBigInteger('group_id')->index()->comment('Agrupamento de exames para impressão. Definição de impressão, se refere a em qual página esse exame deve ser impresso');
            $table->enum('laterality', config('application.laterality'))->nullable()->comment('Lateralidade do exame: OD - Olho direito, OE - Olho esquerdo, AO - Ambos os olhos');

            // Relacionamento
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->foreign('group_id')->references('id')->on('groups');

            $table->text('comment')->nullable()->comment('Comentários livres a serem inseridos');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages_vs_exams');
    }
};
