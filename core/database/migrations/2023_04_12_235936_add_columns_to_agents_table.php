<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->integer('allowed_tickets')->default(0);
            $table->integer('tickets_booked')->default(0);
            $table->timestamp('ticket_booked_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('allowed_tickets');
            $table->dropColumn('tickets_booked');
            $table->dropColumn('ticket_booked_at');
        });
    }
}
