<!DOCTYPE html>
<html lang="en">
<head>
    <title>Due Diligence</title>
    <style type="text/css">
        @page {
            margin: 0;
        }

        section {
            padding: 8mm;
        }

        header {
            padding: 5mm;
            background: #272975;
        }

        .logo-image {
            height: 70px;
        }

        table {
            width: 100%;
            border: 1px solid #8d8d8d;
            border-collapse: collapse;
        }

        h3 {
            color: #272975;
        }

        td {
            margin: 0;
            padding: 8px;
            border: 1px solid #8d8d8d;
        }
        // css example
        span {
        content: "\2713";
        }
    </style>
</head>
<body>
<div>
    <header>
        <img src="https://americankryptosbank.com/img/cb-img/coinbank-logo-light.png"
             class="logo-image"
             alt="American Kryptos Bank">
    </header>
    <section class="body">
        <h1 style="text-align: center; color: #272975;">Due Diligence Checklist</h1>
        <h3>Customer Details</h3>
        <table class="tabla1">
            <tr>
                <td>
                    <span style="font-weight: bold;">ID: </span>{{$user->id}}
                </td>
                <td>
                    <span style="font-weight: bold;">Name: </span>{{$user->personProfile->first_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Second Nombre: </span>@if (isset($user->personProfile->second_name))
                        {{$user->personProfile->second_name}}
                    @else
                        ---
                    @endif
                </td>
                <td>
                    <span style="font-weight: bold;">Last Name: </span>{{$user->personProfile->last_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Second Last Name: </span>@if(isset($user->personProfile->second_last_name))
                    {{$user->personProfile->second_last_name}}
                @else
                    ---
                @endif
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span style="font-weight: bold;">Email: </span>{{$user->personProfile->email}}
                </td>
                <td colspan="2">
                    <span style="font-weight: bold;">Created Date: </span>{{$user->personProfile->created_at}}
                </td>
            </tr>
        </table>
        
        @if ($userWhoMadeTheApproval != null)
        <h3>Details of the trader who approved the profile</h3>
        <table class="tabla1">
            <tr>
                <td>
                    <span style="font-weight: bold;">ID: </span>{{$userWhoMadeTheApproval->id}}
                </td>
                <td>
                    <span style="font-weight: bold;">Name: </span>{{$userWhoMadeTheApproval->personProfile->first_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Second Name: </span>@if (isset($userWhoMadeTheApproval->personProfile->second_name))
                        {{$userWhoMadeTheApproval->personProfile->second_name}}
                    @else
                        ---
                    @endif
                </td>
                <td>
                    <span style="font-weight: bold;">Last Name: </span>{{$userWhoMadeTheApproval->personProfile->last_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Second Last Name: </span>@if(isset($userWhoMadeTheApproval->personProfile->second_last_name))
                    {{$userWhoMadeTheApproval->personProfile->second_last_name}}
                @else
                    ---
                @endif
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span style="font-weight: bold;">Email: </span>{{$userWhoMadeTheApproval->personProfile->email}}
                </td>
                <td colspan="2">
                    <span style="font-weight: bold;">Approval Date: </span>01/04/2020 12:50PM
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <span style="font-weight: bold;">GPS: </span>{{$user->personProfile->approved_by_gps}}
                </td>
            </tr>
        </table>
        @endif
        

        <h3>Approval Data:</h3>
        <table class="tabla1">
            <tr style="text-align: center; font-weight: bold;">
                <td style="width: 100%; background: #eaeaea" colspan="3"></td>
                <td style="text-align: center;">YES</td>
                <td style="text-align: center;">NO</td>
            </tr>
            <tr style="text-align: center; font-weight: bold;">
                <td style="text-align: center; width: 100%; font-weight: bold;" colspan="10">DETALLES</td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Name Check:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Last Name Check:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">ID Type Check:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">ID Number Check:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Review of Issue Date of Identification Document</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision of Expiration Date of Identification Document</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Telephone Number Validation:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Validation of Local Telephone Number:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Validation of Birth Date:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">User Address Validation:</td>
                <td style="text-align: center;">
                    <svg height="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <g>
                            <g>
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                    C514.5,101.703,514.499,85.494,504.502,75.496z"/>
                            </g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                        <g>
                        </g>
                    </svg>
                </td>
                <td style="text-align: center;"></td>
            </tr>
        </table>
    </section>
</div>
</body>
</html>
