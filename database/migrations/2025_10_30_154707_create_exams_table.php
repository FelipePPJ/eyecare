<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration base para gravação de exames.
     * A mesma receberá pedidos de exames que deversão ser realizados por um paciente.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome do exame');

            // Campo grupo separado para outra tabela
            $table->unsignedBigInteger('group_id')->index()->comment('Agrupamento de exames para impressão. Definição de impressão, se refere a em qual página esse exame deve ser impresso');
            $table->enum('laterality', config('application.laterality'))->nullable()->comment('Lateralidade do exame: OD - Olho direito, OE - Olho esquerdo, AO - Ambos os olhos');
            $table->text('comment')->comment('Comentários livres a serem inseridos');

            // Relacionamento
            $table->foreign('group_id')->references('id')->on('groups');

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
        Schema::dropIfExists('exams');
    }
};
