namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
public function edit()
{
if (!Auth::check()) {
return redirect('/login');
}

$user = Auth::user();
return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
if (!Auth::check()) {
return redirect('/login');
}

$user = Auth::user();
$user->update($request->only('name', 'phone', 'email'));

return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
}
}