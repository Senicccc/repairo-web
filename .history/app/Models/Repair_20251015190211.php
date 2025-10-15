namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
use HasFactory;

protected $fillable = [
'user_id',
'phone_brand',
'phone_model',
'imei',
'complaint',
'status',
'technician',
'cost',
];

public function user()
{
return $this->belongsTo(User::class);
}

public function payment()
{
return $this->hasOne(Payment::class);
}
}