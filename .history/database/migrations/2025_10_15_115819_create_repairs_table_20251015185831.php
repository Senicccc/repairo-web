public function up(): void
{
Schema::create('repairs', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
$table->string('phone_brand');
$table->string('phone_model');
$table->string('imei')->nullable();
$table->text('complaint');
$table->enum('status', ['pending', 'in_progress', 'finished', 'cancelled'])->default('pending');
$table->string('technician')->nullable();
$table->decimal('cost', 10, 2)->nullable();
$table->timestamps();
});
}