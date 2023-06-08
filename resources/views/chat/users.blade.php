<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
    <table>
        @foreach($users as $user)
        <tr>
            <form action="/chat" method="get">
                <input type="hidden" name="user_to" value="{{ $user->id }}">
                <button>{{ $user->name }}</button>
            </form>
        </tr>
        @endforeach
    </table>
   
</body>

</html>