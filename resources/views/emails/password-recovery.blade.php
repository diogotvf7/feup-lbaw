<h3>Hi {{ $mailData['name'] }},</h3>
<p>Your new password is: <b>{{ $mailData['password'] }}</b></p>
<p><a href="{{ route('login') }}">Login</a> and change your password!</p>
<h5>-------</h5>
<h5>Geras Staff</h5>
