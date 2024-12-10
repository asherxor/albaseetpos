<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            
            if (!Schema::hasColumn('cash_registers', 'fondo_initial')) {
                $table->decimal('fondo_initial', 20, 4)->default(0)->nullable();
            }
            
            if (!Schema::hasColumn('cash_registers', 'fondo_final')) {
                $table->decimal('fondo_final', 20, 4)->default(0)->nullable();
            }
            
            if (!Schema::hasColumn('cash_registers', 'total_card_amount')) {
                $table->decimal('total_card_amount', 20, 4)->default(0)->nullable();
            }
            
            if (!Schema::hasColumn('cash_registers', 'total_cheque_amount')) {
                $table->decimal('total_cheque_amount', 20, 4)->default(0)->nullable();
            }

            if (!Schema::hasColumn('cash_registers', 'total_other_payment_mod')) {
                $table->decimal('total_other_payment_mod', 20, 4)->default(0)->nullable();
            }

            if (!Schema::hasColumn('cash_registers', 'total_other_payment_amount')) {
                $table->decimal('total_other_payment_amount', 20, 4)->default(0)->nullable();
            }

            if (!Schema::hasColumn('cash_registers', 'banks_transfers')) {
                $table->decimal('banks_transfers', 20, 4)->default(0)->nullable();
            }
            if (!Schema::hasColumn('cash_registers', 'banks_transfers_amount')) {
                $table->decimal('banks_transfers_amount', 20, 4)->default(0)->nullable();
            }
            
            if (!Schema::hasColumn('cash_registers', 'personal_check')) {
                $table->text('personal_check')->nullable();
            }

            if (!Schema::hasColumn('cash_registers', 'declaration')) {
                $table->enum('declaration', ['declared', 'not_declared'])->default('not_declared');
            }
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
