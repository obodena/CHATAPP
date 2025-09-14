use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::connection('message_db')->create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id');  // maybe teacher or student
            $table->string('from_user_type');    // "teacher" or "student"
            $table->foreignId('to_user_id');
            $table->string('to_user_type');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('message_db')->dropIfExists('messages');
    }
}
