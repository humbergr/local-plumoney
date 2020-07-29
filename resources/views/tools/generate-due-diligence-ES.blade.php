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
        <img src="https://media-exp1.licdn.com/dms/image/C4D0BAQEU25_Qjdg27g/company-logo_200_200/0?e=2159024400&v=beta&t=ZLyZP4xc9xWevcJKVutLVQSIIi9Yvg4iRx78g-fJLw0"
             class="logo-image"
             alt="American Kryptos Bank">
    </header>
    <section class="body">
        <h1 style="text-align: center; color: #272975;">Due Diligence Checklist:</h1>
        <h3>Detalles del Cliente</h3>
        <table class="tabla1">
            <tr>
                <td>
                    <span style="font-weight: bold;">ID: </span>{{$user->id}}
                </td>
                <td>
                    <span style="font-weight: bold;">Nombre: </span>{{$user->personProfile->first_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Segundo Nombre: </span>@if (isset($user->personProfile->second_name))
                        {{$user->personProfile->second_name}}
                    @else
                        ---
                    @endif
                </td>
                <td>
                    <span style="font-weight: bold;">Primero Apellido: </span>{{$user->personProfile->last_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Segundo Apellido: </span>@if(isset($user->personProfile->second_last_name))
                    {{$user->personProfile->second_last_name}}
                @else
                    ---
                @endif
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span style="font-weight: bold;">Correo: </span>{{$user->personProfile->email}}
                </td>
                <td colspan="2">
                    <span style="font-weight: bold;">Fecha que se unio: </span>{{$user->personProfile->created_at}}
                </td>
            </tr>
        </table>
        
        @if ($userWhoMadeTheApproval != null)
        <h3>Detalles del Trader que Aprobo</h3>
        <table class="tabla1">
            <tr>
                <td>
                    <span style="font-weight: bold;">ID: </span>{{$userWhoMadeTheApproval->id}}
                </td>
                <td>
                    <span style="font-weight: bold;">Nombre: </span>{{$userWhoMadeTheApproval->personProfile->first_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Segundo Nombre: </span>@if (isset($userWhoMadeTheApproval->personProfile->second_name))
                        {{$userWhoMadeTheApproval->personProfile->second_name}}
                    @else
                        ---
                    @endif
                </td>
                <td>
                    <span style="font-weight: bold;">Primero Apellido: </span>{{$userWhoMadeTheApproval->personProfile->last_name}}
                </td>
                <td>
                    <span style="font-weight: bold;">Segundo Apellido: </span>@if(isset($userWhoMadeTheApproval->personProfile->second_last_name))
                    {{$userWhoMadeTheApproval->personProfile->second_last_name}}
                @else
                    ---
                @endif
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span style="font-weight: bold;">Correo: </span>{{$userWhoMadeTheApproval->personProfile->email}}
                </td>
                <td colspan="2">
                    <span style="font-weight: bold;">Fecha de Aprobación: </span>2020-03-19 17:19:47
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <span style="font-weight: bold;">Localización: </span>{{$user->personProfile->approved_by_gps}}
                </td>
            </tr>
        </table>
        @endif
         

        <h3>Datos Aprobatorios:</h3>
        <table class="tabla1">
            <tr style="text-align: center; font-weight: bold;">
                <td style="width: 100%; background: #eaeaea" colspan="3"></td>
                <td style="text-align: center;">SI</td>
                <td style="text-align: center;">NO</td>
            </tr>
            <tr style="text-align: center; font-weight: bold;">
                <td style="text-align: center; width: 100%; font-weight: bold;" colspan="10">DETALLES</td>
            </tr>
            <tr>
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision de Nombres:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision de Apellidos:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision de Tipo de Documento de identificación:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision de Numero de identificación:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision de Fecha de Emision de Documento de Identificación</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Revision de Fecha de Vencimiento de Documento de Identificación</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Validación de Numero de Telefono:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Validación de Numero de Telefono Local:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Validación de Fecha de Nacimiento:</td>
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
                <td style="width: 90%; background: #eaeaea" colspan="3">Validación de Dirección del Usuario:</td>
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
