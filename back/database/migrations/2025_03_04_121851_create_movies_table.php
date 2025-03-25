<?php  

use Illuminate\Database\Migrations\Migration;  
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\Facades\Schema;  

class CreateMoviesTable extends Migration  
{  
    public function up()  
    {  
        Schema::create('movies', function (Blueprint $table) {  
            $table->id('movie_id');  
            $table->string('title');  
            $table->string('genre');  
            $table->integer('duration');  
            $table->string('director');  
            $table->string('actors');  
            $table->text('synopsis');  
            $table->date('release_date');  
            $table->string('img')->nullable();  
            $table->string('trailer')->nullable();  
            $table->timestamps();  
        });  
    }  

    public function down()  
    {  
        Schema::dropIfExists('movies');  
    }  
}  
