<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="header">
                        <a href="{{ config('app.url') }}">
                            <img src="{{ config('app.url') }}/img/logo.png" alt="nau.io">
                        </a>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    {{-- Body --}}
                                    <h3> User: </h3> <br>
                                    <a href="{{route('users.show', [$user['id']])}}">{{ $user['name'] }}</a> <br>
                                    Phone: {{ $user['phone'] }} <br>
                                    Email: {{ $user['email'] }} <br><br>

                                    <h3> Place: <a
                                                href="{{route('places.show', [$place['id']])}}">{{ $place['name'] }}</a>
                                    </h3><br><br>
                                    <h3> Message: </h3><br>
                                    <p>{{ htmlspecialchars($text) }}</p>
                                    <br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-cell" align="center">
                                    &copy; {{ date('Y') }} NAU. All rights reserved.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
