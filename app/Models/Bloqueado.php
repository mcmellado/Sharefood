<?

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloqueado extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'usuario_bloqueado_id',
    ];

    // Aquí puedes agregar cualquier otra lógica o métodos que necesites
}