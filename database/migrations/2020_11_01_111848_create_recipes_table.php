<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_url');
            $table->string('video_url')->nullable();
            $table->longText('process');
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
        Schema::create('recipes_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->foreignId('product_id')->constrained('products');
            $table->float('unit_value');
            $table->timestamps();
        });
        Schema::create('users_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('recipe_id')->constrained('recipes');
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
        Schema::dropIfExists('recipes_products');
        Schema::dropIfExists('users_recipes');
        Schema::dropIfExists('recipes');

    }
}
