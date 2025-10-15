public function dashboardRedirect()
{
$user = auth()->user();

switch ($user->role) {
case 'user':
return redirect()->route('user.dashboard');
case 'cashier':
return redirect()->route('cashier.dashboard');
case 'technician':
return redirect()->route('technician.dashboard');
case 'admin':
return redirect()->route('admin.dashboard');
default:
return redirect('/');
}
}