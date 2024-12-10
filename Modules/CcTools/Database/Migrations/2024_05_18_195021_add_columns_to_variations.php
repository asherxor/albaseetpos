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
        Schema::table('variations', function (Blueprint $table) {
            
            if (!Schema::hasColumn('variations', 'modifier_qty')) {
                $table->decimal('modifier_qty', 10, 2)->nullable();
            }
        
            if (!Schema::hasColumn('variations', 'modifier_product')) {
                $table->bigInteger('modifier_product')->nullable();
            }

            if (!Schema::hasColumn('variations', 'color_button_modifier')) {
                $table->string('color_button_modifier')->nullable();
            }
        
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'status_modifier')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->enum('status_modifier', ['enabled', 'disabled'])->default('enabled')->nullable();
                });
            }
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {/*
        Schema::table('variations', function (Blueprint $table) {
            if (Schema::hasColumn('variations', 'modifier_qty')) {
                $table->dropColumn('modifier_qty');
            }
        
            if (Schema::hasColumn('variations', 'modifier_product')) {
                $table->dropColumn('modifier_product');
            }

            if (Schema::hasColumn('variations', 'color_button_modifier')) {
                $table->dropColumn('color_button_modifier');
            }
            
        
        });
        Schema::table('products', function (Blueprint $table) {
            
            if(Schema::hasColumn('products','status_modifier')) {
                $table->dropColumn('status_modifier');
            }
        
        });
        */
    }
};
