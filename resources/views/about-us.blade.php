@extends('layouts.mvp-layout')

@section('content')

    <main>
        <div class="triangle--toRight">
            <section id="about-us" class="py-section-2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 my-auto">
                            <div class="wow fadeInUp">
                                <h1 class="text-primary font-weight-bold">Creciendo <span
                                            class="text-secondary">contigo</span></h1>
                                <h3 class="font-weight-light mb-4 mb-md-5">Desde cualquier lugar del mundo</h3>
                                <p>American Kryptos Bank, ofrece a toda la comunidad entrar en el futuro de las finanzas
                                    descentralizadas, donde los individuos y empresas tendrán el verdadero control de su
                                    dinero.</p>
                                <p>Para esto American Kryptos Bank, pone al alcance la tecnología de la red BlockChain
                                    que interconectara a todo el mundo con un solo click, permitiendo realizar
                                    transferencias, pagos a proveedores, remesas familiares: desde el confort de tu casa
                                    u oficina.</p>
                                <p><span class="text-primary font-weight-bold">¿Que esperas?</span> conéctate ya con
                                    nosotros y conoce más sobre el maravilloso mundo que ofrece American Kryptos Bank
                                    para ti.</p>
                            </div>
                        </div>
                        <div class="col-md-6 offset-md-1">
                            <img src="img/landing/img186.png" alt="" class="img-fluid wow fadeInUp">
                        </div>
                    </div>
                </div>
            </section>

            <section id="mision-vision" class="py-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 my-auto px-0 wow fadeInUp" style="z-index: 10;">
                            <div class="bg-secondary text-white px-3 px-md-4 py-4">
                                <div class="row">
                                    <div class="col-6 col-md-4 offset-md-2 mb-4 mb-md-0 text-center text-md-left">
                                        <div class="media flex-column flex-md-row align-items-center align-items-md-start mb-4">
                                            <img src="img/landing/mision-icon.png"
                                                 class="img-fluid mr-md-3 mb-2 mb-md-0">
                                            <div class="media-body mt-auto mb-n2">
                                                <h6 class="text-uppercase font-weight-bold lh-125 mb-0">Nuestra</h6>
                                                <h4 class="text-uppercase font-weight-bold lh-125 mb-0">Misión</h4>
                                            </div>
                                        </div>
                                        <p class="font-weight-light font-14 mb-0">Buscamos entregar el control de sus
                                            finanzas a todos nuestros clientes, mediante nuestro sofisticado software
                                            establecido en la red BlockChain, que facilitara el desarrollo de sus
                                            actividades económicas alrededor de todo el mundo.</p>
                                    </div>
                                    <div class="col-6 col-md-4 offset-md-1 mb-4 mb-md-0 text-center text-md-left">
                                        <div class="media flex-column flex-md-row align-items-center align-items-md-start mb-4">
                                            <img src="img/landing/vision-icon.png"
                                                 class="img-fluid mr-md-3 mb-2 mb-md-0">
                                            <div class="media-body mt-auto mb-n2">
                                                <h6 class="text-uppercase font-weight-bold lh-125 mb-0">Nuestra</h6>
                                                <h4 class="text-uppercase font-weight-bold lh-125 mb-0">Visión</h4>
                                            </div>
                                        </div>
                                        <p class="font-weight-light font-14 mb-0">Establecernos como la empresa líder en
                                            el mercado de transacciones financieras basadas en la red BlockChain,
                                            siempre encaminados a desarrollar las nuevas tendencias tecnológicas en el
                                            mundo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md px-0 text-right ml-md-n5 wow fadeIn">
                            <img src="img/landing/tiempo_libre.png" alt="" class="d-none d-md-block object-cover"
                                 style="pointer-events: none; user-select: none; -webkit-user-select: none;">
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="py-section-3 triangle--toLeft">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="text-center wow fadeInUp">
                            <img src="img/landing/akb-logo.svg" alt="American Kryptos Bank Logo" class="img-fluid mb-4">
                        </div>
                        <h2 class="text-primary font-weight-bold text-justify mb-4 wow fadeInUp">Unimos al mundo Entero
                            a través de la red Blockchain</h2>
                        {{--<p class="text-primary font-14 font-weight-light wow fadeInUp">Lorem ipsum dolor sit amet,
                            consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat.</p>--}}
                        <div class="text-center wow fadeInUp">
                            <a href="#!" class="btn btn-secondary btn-pill px-4 mt-md-5">Ver todos los servicios</a>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <div class="row mt-5 mt-md-0">
                            <div class="col-4 col-md-6 px-2 px-md-3">
                                <div class="redBox px-2 py-3 py-md-4 mb-3 text-center text-white wow fadeInUp">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="77" height="73"
                                             viewBox="0 0 77 73">
                                            <g>
                                                <g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <g>
                                                                                <path fill="#f7941d"
                                                                                      d="M21.717 8.276a6.035 6.035 0 0 1-6.029 6.03 6.035 6.035 0 0 1-6.03-6.03 6.038 6.038 0 0 1 6.03-6.03 6.037 6.037 0 0 1 6.03 6.03zm1.622 0c0-4.218-3.432-7.652-7.65-7.652-4.22 0-7.652 3.434-7.652 7.652s3.432 7.652 7.651 7.652c4.22 0 7.651-3.434 7.651-7.652z"/>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <g>
                                                                                <path fill="#f7941d"
                                                                                      d="M15.373 22.45h.408l1.255 10.923-1.46 1.178-1.458-1.178zm3.376 11.624L17.227 20.83h-3.3l-1.522 13.245 3.172 2.562z"/>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                            <g>
                                                                <path fill="#f7941d"
                                                                      d="M30.508 45.714a2.625 2.625 0 0 1-4.48 1.856 2.606 2.606 0 0 1-.764-1.798v-1.8l.002-.939h5.242zM8.38 48.337v-2.562c0-.02.003-.039.003-.061v-4.303h-.003V30.112H6.76v11.299H1.507V26.847c0-4.266 3.469-7.734 7.731-7.734h2.193l2.845 3.338h2.584l3.067-3.338h2.858c4.263 0 7.732 3.468 7.732 7.734V41.41h-5.253V30.112h-1.622v13.854l-.002 1.743.002.063v2.565zm-4.242 0a2.614 2.614 0 0 1-2.623-2.624l.005-2.68H6.76v2.742a2.622 2.622 0 0 1-2.62 2.562zm13.586-29.224l-1.577 1.717h-1.124l-1.463-1.717zm14.413 7.734c0-5.16-4.196-9.356-9.353-9.356H9.238c-5.157 0-9.353 4.197-9.353 9.356v17.976h.011l-.002.886a4.206 4.206 0 0 0 1.241 3.004 4.224 4.224 0 0 0 5.624.335v.915h18.505v-.912a4.21 4.21 0 0 0 2.62.912 4.252 4.252 0 0 0 4.245-4.249v-.89h.01z"/>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="#f7941d"
                                                                                  d="M66.256 30.523a6.037 6.037 0 0 1-6.03 6.03 6.037 6.037 0 0 1-6.03-6.03 6.037 6.037 0 0 1 6.03-6.029 6.037 6.037 0 0 1 6.03 6.03zm1.621 0c0-4.218-3.432-7.65-7.651-7.65-4.22 0-7.652 3.432-7.652 7.65 0 4.22 3.433 7.65 7.652 7.65s7.651-3.43 7.651-7.65z"/>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <path fill="#f7941d"
                                                                  d="M64.791 13.373l-3.721 3.723v-3.954c0-6.402-5.21-11.611-11.612-11.611H32.352v1.621h17.106c5.508 0 9.99 4.481 9.99 9.99v3.841l-3.72-3.722-1.148 1.147 5.734 5.737 5.624-5.624z"/>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M75.045 67.966a2.626 2.626 0 0 1-4.48 1.853 2.605 2.605 0 0 1-.763-1.8V66.39l.002-1.108h5.241zM52.92 70.59V68.03c0-.022.003-.044.003-.064v-4.305h-.003V52.365h-1.622V63.66h-5.252V49.096c0-4.264 3.468-7.73 7.73-7.73h1.703c.393 2.553 2.602 4.52 5.266 4.52 2.665 0 4.872-1.967 5.265-4.52h1.314c4.263 0 7.731 3.466 7.731 7.73v14.565h-5.252V52.365H68.18v13.922l-.003 1.67.003.06v2.572zm-4.242 0a2.61 2.61 0 0 1-1.857-.77 2.605 2.605 0 0 1-.766-1.86l.004-2.677h5.239v2.748a2.623 2.623 0 0 1-2.62 2.559zm15.69-29.224a3.716 3.716 0 0 1-3.623 2.899 3.716 3.716 0 0 1-3.623-2.899zm12.309 7.73c0-5.155-4.196-9.352-9.353-9.352h-1.245v-.002H55.41v.002h-1.634c-5.157 0-9.353 4.197-9.353 9.353v17.98h.01v.882a4.219 4.219 0 0 0 1.24 3.007 4.214 4.214 0 0 0 3.004 1.245c.989 0 1.898-.34 2.62-.91v.91h18.505v-.908a4.206 4.206 0 0 0 2.62.908 4.249 4.249 0 0 0 4.245-4.244v-.89h.009z"/>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#f7941d"
                                                                              d="M59.788 50.478a.961.961 0 1 1 1.922 0 .961.961 0 0 1-1.922 0z"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#f7941d"
                                                                              d="M59.788 56.856a.961.961 0 1 1 1.922 0 .961.961 0 0 1-1.922 0z"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#f7941d"
                                                                              d="M59.788 63.234a.961.961 0 1 1 1.922 0 .961.961 0 0 1-1.922 0z"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <text fill="#f7941d" style="font-kerning:normal"
                                                                  dominant-baseline="text-before-edge"
                                                                  font-family="'Mukta','Mukta-Light'" font-size="23"
                                                                  font-style="none" font-weight="lighter"
                                                                  transform="translate(38 -1)">
                                                                <tspan>$</tspan>
                                                            </text>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="font-weight-light text-truncate">Envía</h5>
                                </div>
                            </div>
                            <div class="col-4 col-md-6 px-2 px-md-3">
                                <div class="redBox px-2 py-3 py-md-4 mb-3 text-center text-white wow fadeInUp">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72"
                                             viewBox="0 0 72 72">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M22.253 44.505C9.983 44.505 0 34.523 0 22.252 0 9.983 9.983 0 22.253 0s22.252 9.983 22.252 22.252H42.28c0-11.042-8.985-20.027-20.027-20.027-11.043 0-20.028 8.985-20.028 20.027 0 11.043 8.985 20.028 20.027 20.028z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M10.014 21.14v-2.225h12.239v2.225z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M10.014 25.59v-2.225h12.239v2.225z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M22.252 32.267c-5.52 0-10.014-4.493-10.014-10.015 0-5.52 4.493-10.014 10.014-10.014 2.888 0 5.493 1.293 7.532 3.738l-1.71 1.426c-1.626-1.95-3.583-2.938-5.822-2.938-4.294 0-7.788 3.494-7.788 7.788 0 4.295 3.494 7.789 7.788 7.789 2.24 0 4.196-.988 5.822-2.938l1.71 1.425c-2.039 2.446-4.644 3.74-7.532 3.74z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M68.984 48.957C68.984 60 60 68.984 48.957 68.984c-11.044 0-20.03-8.984-20.03-20.027 0-11.044 8.986-20.03 20.03-20.03 11.043 0 20.027 8.986 20.027 20.03zm2.226 0c0-12.27-9.983-22.254-22.253-22.254s-22.254 9.983-22.254 22.254c0 12.27 9.983 22.253 22.254 22.253 12.27 0 22.253-9.983 22.253-22.253z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M48.957 50.069c-1.446 0-2.83-.59-3.903-1.661-1.072-1.072-1.66-2.457-1.66-3.903 0-1.445.588-2.83 1.66-3.902s2.457-1.66 3.903-1.66c1.445 0 2.83.588 3.902 1.66 1.07 1.073 1.66 2.457 1.66 3.902h-2.224c0-.851-.36-1.677-1.01-2.327a3.264 3.264 0 0 0-4.658 0c-.65.65-1.009 1.476-1.009 2.327 0 .85.358 1.678 1.009 2.329.652.652 1.478 1.009 2.33 1.009z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M48.957 58.97c-1.446 0-2.83-.59-3.903-1.66-1.072-1.073-1.66-2.459-1.66-3.904h2.224c0 .853.358 1.679 1.009 2.33a3.264 3.264 0 0 0 4.658 0c.65-.651 1.01-1.477 1.01-2.33 0-.85-.36-1.678-1.01-2.328-.651-.65-1.478-1.01-2.328-1.01v-2.225c1.445 0 2.83.59 3.902 1.662 1.07 1.07 1.66 2.457 1.66 3.901 0 1.445-.59 2.831-1.66 3.903-1.072 1.071-2.457 1.661-3.902 1.661z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M47.844 40.055v-3.338h2.225v3.338z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M47.844 61.195v-3.337h2.225v3.337z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M36.717 50.069v-2.226h2.225v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M58.97 50.069v-2.226h2.226v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M50.069 10.013h-2.225V7.788h2.225zm4.45 0h-2.224V7.788h2.225zm4.451 0h-2.226V7.788h2.226zm4.451 0h-2.226V7.788h2.226zm0 4.45h-2.226v-2.225h2.226zm0 4.452h-2.226V16.69h2.226zm0 4.45h-2.226v-2.224h2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M10.013 50.069H7.788v-2.226h2.225zm0 4.45H7.788v-2.224h2.225zm0 4.451H7.788v-2.226h2.225zm0 4.451H7.788v-2.226h2.225zm4.45 0h-2.225v-2.226h2.226zm4.452 0H16.69v-2.226h2.225zm4.45 0h-2.224v-2.226h2.225z"/>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="font-weight-light text-truncate">Convierte</h5>
                                </div>
                            </div>
                            <div class="col-4 col-md-6 px-2 px-md-3">
                                <div class="redBox px-2 py-3 py-md-4 mb-3 text-center text-white wow fadeInUp">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72"
                                             viewBox="0 0 72 72">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M12.24 65.647h-2.225V60.64c0-.712-.075-1.333-.44-2.062l-.046-.108c-.28-.747-.784-1.311-1.191-1.718l-7.12-7.12a5.055 5.055 0 0 1-.987-1.403l-.119-.233v-.263c0-.19-.027-.377-.052-.552a5.394 5.394 0 0 1-.06-.672V25.591c0-1.192.447-2.244 1.328-3.123.881-.88 1.931-1.327 3.123-1.327 1.191 0 2.243.446 3.124 1.327.88.881 1.327 1.931 1.327 3.123v12.24H6.676V25.59c0-.597-.214-1.089-.675-1.55-.921-.921-2.179-.921-3.1 0-.46.461-.675.953-.675 1.55v20.918c0 .11.022.238.04.377.023.177.048.363.062.552.141.253.28.438.464.624l7.12 7.118c.515.514 1.244 1.322 1.68 2.456.57 1.163.648 2.17.648 3.004z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M27.817 65.647h-2.226V52.628c0-.964-.197-1.95-.59-2.924a7.533 7.533 0 0 0-1.644-2.52l-7.355-7.578a1.993 1.993 0 0 0-1.449-.663c-.627-.035-1.19.226-1.638.674a2.006 2.006 0 0 0-.674 1.462 2.24 2.24 0 0 0 .674 1.637l5.73 6.623c1.383 1.383 1.383 2.872 1.383 4.069h-2.226c0-1.026 0-1.765-.786-2.553l-5.728-6.623c-.815-.81-1.298-1.997-1.273-3.2a4.182 4.182 0 0 1 1.362-3.02c.833-.837 2.019-1.298 3.224-1.293a4.176 4.176 0 0 1 3.02 1.362l7.324 7.542a9.787 9.787 0 0 1 2.125 3.254c.495 1.24.747 2.501.747 3.751z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M31.155 71.21h-2.227v-4.451H8.902v4.45H6.676v-6.675h24.479z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M23.366 71.21v-2.226h2.225v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M61.196 65.647H58.97V60.64c0-.834.079-1.84.649-3.004.438-1.134 1.167-1.942 1.68-2.456l7.122-7.12a2.99 2.99 0 0 0 .572-.825c.103-.21.103-.46.103-.726V25.591c0-.597-.214-1.089-.674-1.55-1.054-.896-2.287-.926-3.212 0-.462.461-.676.953-.676 1.55v12.24h-2.226V25.59c0-1.192.447-2.244 1.328-3.124.88-.88 1.931-1.326 3.123-1.326 1.138 0 2.175.415 3.171 1.269.945.939 1.39 1.99 1.39 3.18v20.92c0 .388 0 1.04-.338 1.72-.292.58-.586.999-.987 1.402l-7.123 7.121c-.407.406-.91.972-1.19 1.717l-.046.108c-.366.729-.44 1.35-.44 2.062z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M45.619 65.647h-2.225V52.628c0-1.248.251-2.51.746-3.751.538-1.344 1.358-2.379 2.088-3.214l7.383-7.605a4.495 4.495 0 0 1 3.27-1.34 4.174 4.174 0 0 1 3.02 1.363 4.454 4.454 0 0 1 1.294 3.222 4.18 4.18 0 0 1-1.312 2.974l-5.633 6.52c-.842.846-.842 1.585-.842 2.61H51.18c0-1.196 0-2.685 1.44-4.124l5.708-6.6c.401-.367.629-.874.641-1.427a2.239 2.239 0 0 0-.675-1.638 1.996 1.996 0 0 0-1.462-.673c-.604-.054-1.188.224-1.636.673l-7.332 7.554c-.6.685-1.247 1.502-1.657 2.532-.39.976-.589 1.96-.589 2.924z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M64.534 71.21h-2.226v-4.451H42.28v4.45h-2.226v-6.675h24.48z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M45.619 71.21v-2.226h2.225v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M34.492 57.858V27.816h2.226v30.042z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M34.492 71.21v-2.226h2.226v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M34.492 66.759v-2.225h2.226v2.225z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M34.492 62.308v-2.225h2.226v2.225z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M35.606 15.577c-1.126 0-2.294-.496-3.124-1.327-.881-.88-1.327-1.932-1.327-3.124 0-1.191.446-2.243 1.327-3.122.88-.881 1.932-1.329 3.124-1.329 1.191 0 2.243.446 3.122 1.329.881.88 1.327 1.93 1.327 3.122H37.83c0-.596-.215-1.088-.675-1.55-.922-.922-2.178-.922-3.1 0-.46.462-.676.954-.676 1.55 0 .597.216 1.09.677 1.55.498.498 1.127.677 1.55.677z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M35.606 22.253c-1.126 0-2.294-.497-3.124-1.327-.881-.88-1.327-1.933-1.327-3.124h2.224c0 .597.216 1.09.677 1.551.498.497 1.127.675 1.55.675.596 0 1.088-.215 1.55-.675.498-.498.674-1.128.674-1.551 0-.597-.215-1.089-.675-1.55-.46-.46-.953-.675-1.55-.675v-2.224c1.192 0 2.244.445 3.123 1.326.881.88 1.327 1.931 1.327 3.123 0 1.125-.495 2.294-1.327 3.126-.88.879-1.93 1.325-3.122 1.325z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M34.492 7.789V4.451h2.226V7.79z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M34.492 24.479V21.14h2.226v3.338z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M47.844 14.464c0 6.75-5.49 12.24-12.238 12.24-6.748 0-12.24-5.49-12.24-12.24 0-6.748 5.492-12.238 12.24-12.238s12.238 5.49 12.238 12.238zm2.226 0C50.07 6.49 43.58 0 35.606 0 27.629 0 21.14 6.49 21.14 14.464c0 7.976 6.488 14.464 14.465 14.464 7.974 0 14.464-6.488 14.464-14.464z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M43.394 15.578v-2.226h2.225v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d" d="M25.591 15.578v-2.226h2.226v2.226z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M41.168 35.606c0-.852.358-1.68 1.01-2.33.65-.652 1.478-1.008 2.328-1.008h3.338v3.338c0 .85-.357 1.677-1.009 2.327-.65.65-1.477 1.01-2.33 1.01h-3.337zm7.24 3.902c1.071-1.072 1.662-2.457 1.662-3.902v-5.564h-5.564c-1.445 0-2.832.59-3.902 1.661-1.072 1.072-1.66 2.456-1.66 3.903v5.562h5.562c1.445 0 2.83-.59 3.902-1.66z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M26.704 32.268c.852 0 1.677.356 2.33 1.008.65.65 1.008 1.478 1.008 2.33v3.337h-3.338c-.852 0-1.678-.36-2.33-1.01-.65-.65-1.008-1.477-1.008-2.327v-3.338zm5.564 3.338c0-1.447-.59-2.832-1.662-3.903-1.07-1.071-2.457-1.661-3.902-1.661H21.14v5.564c0 1.445.59 2.83 1.66 3.902 1.072 1.07 2.458 1.66 3.903 1.66h5.564z"/>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="font-weight-light text-truncate">Invierte</h5>
                                </div>
                            </div>
                            <div class="col-4 col-md-6 px-2 px-md-3">
                                <div class="redBox px-2 py-3 py-md-4 mb-3 text-center text-white wow fadeInUp">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="94" height="75"
                                             viewBox="0 0 94 75">
                                            <g>
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path fill="#f7941d"
                                                                  d="M51.17 13.237v.023c0 .749-.277 1.377-.785 1.825h-4.828a4.807 4.807 0 0 1-.836-.651l.725-.869c.78.758 1.783 1.15 2.675 1.15 1.026 0 1.783-.508 1.783-1.38 0-.033 0-.065-.004-.098z"/>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="#f7941d"
                                                                                  d="M88.79 38.433h-5.144v-1.848h5.144a2.639 2.639 0 0 0 2.636-2.636 2.079 2.079 0 0 0-2.077-2.077c-1.18 0-2.11.932-2.11 2.077H85.39a3.929 3.929 0 0 1 3.925-3.925c2.198 0 3.959 1.76 3.959 3.925a4.489 4.489 0 0 1-4.484 4.484z"/>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <g>
                                                                                <path fill="#f7941d"
                                                                                      d="M55.79 15.085a8.715 8.715 0 0 1-2.384 2.938H42.485a8.617 8.617 0 0 1-2.398-2.938 8.776 8.776 0 0 1-.929-3.945c0-4.847 3.941-8.792 8.788-8.792 4.846 0 8.791 3.945 8.791 8.792a8.58 8.58 0 0 1-.258 2.097 8.38 8.38 0 0 1-.689 1.848zM47.946.5C42.083.5 37.31 5.272 37.31 11.14c0 .711.074 1.413.213 2.097.13.633.314 1.252.55 1.848a10.657 10.657 0 0 0 1.774 2.938c.522.62 1.113 1.179 1.774 1.668l.245.18h12.16l.244-.18a10.364 10.364 0 0 0 1.784-1.668 10.628 10.628 0 0 0 2.32-4.786c.137-.684.211-1.386.211-2.097C58.585 5.272 53.813.5 47.945.5z"/>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <path fill="#f7941d"
                                                                                          d="M48.172 10.484c-1.16-.38-2-.698-2-1.566 0-.813.679-1.266 1.649-1.266.684 0 1.603.29 2.333.822l.698-.864c-.615-.48-1.525-.86-2.361-1.007V4.787h-.929v1.756c-1.695.078-2.638 1.104-2.638 2.449 0 1.653 1.386 2.13 2.901 2.614 1.239.388 2.024.712 2.075 1.631.004.033.004.065.004.097 0 .873-.757 1.382-1.783 1.382-.892 0-1.894-.393-2.675-1.15l-.725.868c.263.254.54.471.836.651a4.678 4.678 0 0 0 2.005.712v1.69h.929v-1.676c.794-.065 1.441-.32 1.894-.726.508-.448.785-1.076.785-1.825v-.023c-.014-1.76-1.303-2.217-2.998-2.753z"/>
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="#f7941d"
                                                                                  d="M37.754 19.871v-1.848h19.584v1.848z"/>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <path fill="#f7941d"
                                                                  d="M60.309 13.237h-3.83a8.383 8.383 0 0 1-.689 1.848h4.519c11.17 0 20.263 9.088 20.263 20.259v5.798c0 8.787-5.678 16.433-13.722 19.182v-6.639h-1.848v19.247h-9.138V58.227h-1.848V61.4H38.364v-3.174h-1.848v14.705h-9.139V53.685H25.53v6.551a20.405 20.405 0 0 1-12.192-11.993c-.009-.019-.836-2.093-1.178-5.128l-.092-.823H2.596V31.03h13.536V29.18h-3.118a19.766 19.766 0 0 1 1.626-3.742l.116-.213.004-10.22c0-.812.448-1.482 1.197-1.792a1.9 1.9 0 0 1 2.12.42l7.72 7.725 1.308-1.307-3.16-3.16a20.303 20.303 0 0 1 8.376-1.807h7.766a8.507 8.507 0 0 1-.68-1.848h-7.086c-3.387 0-6.75.781-9.767 2.264l-3.174-3.174c-1.104-1.104-2.684-1.418-4.13-.822-1.441.6-2.338 1.94-2.338 3.502v9.739a22.107 22.107 0 0 0-1.834 4.435H.748v14.96h9.683c.412 2.845 1.16 4.712 1.183 4.776 2.36 6.288 7.544 11.218 13.915 13.269V74.78h12.835V63.249h15.652V74.78H66.85V62.264c9.093-2.813 15.57-11.318 15.57-21.122v-5.798c0-12.192-9.92-22.107-22.111-22.107z"/>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#f7941d"
                                                                              d="M24.563 33.932v-1.848h3.777v1.848z"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="font-weight-light text-truncate">Ahorra</h5>
                                </div>
                            </div>
                            <div class="col-4 col-md-6 px-2 px-md-3">
                                <div class="redBox px-2 py-3 py-md-4 mb-3 text-center text-white wow fadeInUp">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="77" height="72"
                                             viewBox="0 0 77 72">
                                            <g>
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path fill="#f7941d"
                                                                  d="M51.617 25.928V6.566A6.499 6.499 0 0 0 45.124.073H15.797a6.499 6.499 0 0 0-6.493 6.493v58.15a6.499 6.499 0 0 0 6.493 6.493h29.327a6.499 6.499 0 0 0 6.493-6.493V29.253l-1.764-.983v36.446a4.732 4.732 0 0 1-4.73 4.73H15.798a4.735 4.735 0 0 1-4.729-4.73V6.566a4.735 4.735 0 0 1 4.73-4.73h29.326a4.732 4.732 0 0 1 4.729 4.73v20.151z"/>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="#f7941d"
                                                                                  d="M27.437 6.205V4.44h6.05v1.765z"/>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="#f7941d"
                                                                                  d="M32.935 65.449h-4.95a1.348 1.348 0 0 1 0-2.696h4.95a1.35 1.35 0 0 1 0 2.695zm0-4.46h-4.95a3.112 3.112 0 0 0 0 6.225h4.95a3.116 3.116 0 0 0 3.115-3.111 3.12 3.12 0 0 0-3.115-3.114z"/>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <path fill="#f7941d"
                                                                  d="M43.483 56.595H17.43V10.483h26.053zm1.764-46.112h.009V8.72H15.665v49.64h29.591v-1.764h-.009z"/>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <g>
                                                                            <path fill="#f7941d"
                                                                                  d="M30.813 34.04c-1.826-.6-3.15-1.103-3.15-2.47 0-1.284 1.068-1.999 2.595-1.999 1.08 0 2.532.459 3.687 1.293l1.099-1.354c-.97-.76-2.404-1.36-3.728-1.593V25.05h-1.464v2.77c-2.67.128-4.156 1.743-4.156 3.868 0 2.608 2.18 3.353 4.575 4.125 2.016.631 3.277 1.152 3.277 2.722 0 1.38-1.195 2.184-2.81 2.184-1.411 0-2.99-.622-4.221-1.818l-1.143 1.368c1.293 1.248 2.854 1.967 4.478 2.148v2.668h1.464V42.44c2.66-.216 4.23-1.787 4.23-4.019 0-2.81-2.037-3.528-4.733-4.38z"/>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M64.176 36.095l-4.813-2.598-2.973-1.606 17.037-14.407zm-8.465 1.69v-4.252l1.99 1.071zm-4.094-8.531l-1.764-.984-1.549-.865 1.549-.689 1.764-.789 19.198-8.548-16.088 13.608zm25.171-14.616l-1.57-.983-.16-.097L51.619 24l-1.765.785-4.606 2.052-.882.391.882.494 4.606 2.568 1.764.984 1.672.93-.018.013.675.367v8.28l1.632.468 3.675-5.885 5.69 3.065 11.863-23.86z"/>
                                                    </g>
                                                    <g>
                                                        <path fill="#f7941d"
                                                              d="M38.075 16.427l-1.248 1.248 2.895 2.895H9.144c-4.922 0-8.927 4.005-8.927 8.927 0 4.923 4.005 8.927 8.927 8.927H21.94V36.66H9.144c-3.95 0-7.163-3.213-7.163-7.163 0-3.949 3.213-7.162 7.163-7.162H40.39l-3.659 3.66 1.248 1.246 5.455-5.455z"/>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="font-weight-light text-truncate">Transfiere</h5>
                                </div>
                            </div>
                            <div class="col-4 col-md-6 px-2 px-md-3">
                                <div class="redBox px-2 py-3 py-md-4 mb-3 text-center text-white wow fadeInUp">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="71"
                                             viewBox="0 0 72 71">
                                            <g>
                                                <g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#f7941d"
                                                                              d="M69.517 35.538c0 18.5-15.052 33.553-33.554 33.553-18.501 0-33.553-15.052-33.553-33.553 0-18.503 15.052-33.555 33.553-33.555 18.502 0 33.554 15.052 33.554 33.555zm1.91 0C71.427 15.982 55.52.073 35.963.073 16.408.073.5 15.982.5 35.538.5 55.092 16.41 71 35.963 71c19.556 0 35.464-15.909 35.464-35.463z"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#f7941d"
                                                                              d="M30.44 23.075l10.416.002c1.99 0 3.61 1.617 3.61 3.609v.534c0 1.99-1.62 3.609-3.61 3.609l-10.417.002zm10.416 9.665a5.526 5.526 0 0 0 5.52-5.52v-.534a5.531 5.531 0 0 0-4.564-5.438v-.082H28.529V32.74zM30.44 35.5l12.97.004a5.19 5.19 0 0 1 5.185 5.186v.674a5.191 5.191 0 0 1-5.186 5.185l-12.969.001zm12.97 12.957c3.912 0 7.095-3.183 7.095-7.093v-.674c0-3.915-3.183-7.099-7.096-7.099l-14.88-.002v14.872H40.6zm-23.456-.08a5.492 5.492 0 0 0 4.527-5.4V26.646a5.492 5.492 0 0 0-4.527-5.4v-.933h10.804v-5.778h1.92v5.778h4.918v-5.778h1.92v5.778h3.855v-.002a6.382 6.382 0 0 1 6.374 6.375v.534a6.342 6.342 0 0 1-1.772 4.398l-1.03 1.075 1.409.486a7.958 7.958 0 0 1 5.367 7.512v.674c0 4.383-3.567 7.948-7.95 7.948l-6.253-.002v5.78l-1.92.001v-5.781h-4.918v5.78l-1.92.001v-5.781H19.953zm21.472 8.623v-5.777h4.343c5.436 0 9.86-4.422 9.86-9.86v-.673a9.866 9.866 0 0 0-5.426-8.799 8.214 8.214 0 0 0 1.452-4.672v-.534c0-4.246-3.21-7.755-7.33-8.23v-.053h-.801c-.05-.002-.102-.002-.153-.002l-1.945.002v-5.78l-5.74.003v5.777h-1.098v-5.78l-5.74.002v5.778H18.042v4.672h.955a3.575 3.575 0 0 1 3.572 3.571v16.332a3.576 3.576 0 0 1-3.572 3.573h-.955v4.671h10.805v5.781l5.74-.002v-5.779h1.098v5.781z"/>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="font-weight-light text-truncate">Compra BTC</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-to-help" class="py-section-3">
            <div class="container">
                <h1 class="text-primary font-weight-bold text-center wow fadeIn">¿Tienes dudas?</h1>
                <div class="text-center mb-5 wow fadeIn">Consulta nuestras preguntas frecuentes<span
                            class="d-none d-md-inline">, estamos seguros que encontrarás lo que buscas</span></div>
                <div class="row">
                    <div class="col-lg-8 mx-auto">

                        <div class="accordion" id="faqs-accordion">
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingOne" data-toggle="collapse"
                                     data-target="#questionOne" aria-expanded="false" aria-controls="questionOne">
                                    <h6 class="float-left mb-0">¿A qué países puedo enviar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>

                                <div id="questionOne" class="collapse" aria-labelledby="headingOne"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Puedes enviar dinero desde y hasta:
                                        <br>
                                        <ul>
                                            <li>
                                                Venezuela
                                            </li>
                                            <li>
                                                Argentina
                                            </li>
                                            <li>
                                                Bolivia
                                            </li>
                                            <li>
                                                Brasil
                                            </li>
                                            <li>
                                                Chile
                                            </li>
                                            <li>
                                                Colombia
                                            </li>
                                            <li>
                                                Perú
                                            </li>
                                            <li>
                                                Paraguay
                                            </li>
                                            <li>
                                                Uruguay
                                            </li>
                                            <li>
                                                México
                                            </li>
                                            <li>
                                                United States
                                            </li>
                                            <li>
                                                Reino Unido
                                            </li>
                                            <li>
                                                Canada
                                            </li>
                                            <li>
                                                España
                                            </li>
                                            <li>
                                                Italia
                                            </li>
                                            <li>
                                                Portugal
                                            </li>
                                        </ul>

                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingTwo" data-toggle="collapse"
                                     data-target="#questionTwo" aria-expanded="false" aria-controls="questionTwo">
                                    <h6 class="float-left mb-0">¿Que necesito para comenzar a enviar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionTwo" class="collapse" aria-labelledby="headingTwo"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Debes registrarte en nuestra plataforma. Con un registro simple podrás enviar
                                        hasta <strong>100 USD diarios</strong>.
                                        <br>
                                        <br>
                                        Si deseas enviar montos superiores deberás completar un <strong>perfil de
                                            usuario o empresa</strong>, indicándonos datos que nos ayudarán a validar tu
                                        identidad y brindarte mayor seguridad.
                                        <br>
                                        <br>
                                        Puedes contactarnos si necesitas algunas condiciones especiales. Queremos
                                        escucharte.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingFour" data-toggle="collapse"
                                     data-target="#questionFour" aria-expanded="false" aria-controls="questionFour">
                                    <h6 class="float-left mb-0">¿Cómo puedo pagar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionFour" class="collapse" aria-labelledby="headingFour"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Puedes pagar por diferentes métodos:
                                        <br>
                                        <br>
                                        <ul>
                                            <li>Tarjetas de Crédito o Débito</li>
                                            <li>
                                                Diferentes sericios internacionales de pago como:
                                                <ul>
                                                    <li>Venmo</li>
                                                    <li>Cash App</li>
                                                    <li>Payoneer</li>
                                                    <li>Zelle</li>
                                                    <li>PopMoney</li>
                                                    <li>Pandco</li>
                                                </ul>
                                            </li>
                                            <li>Efectivo o Transferencia desde el país en el que estás</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5 wow fadeIn">
                            <a href="/help" class="btn btn-secondary btn-pill px-lg-4">Ver más preguntas
                                frecuentes</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{--<section id="clients" class="py-section-2">
          <div class="container">
              <h1 class="text-primary font-weight-bold text-center mb-5 wow fadeIn">Nuestros clientes felices,<br> confirman nuestro propósito</h1>
              <div class="row">
                  <div class="col-lg-8 mx-auto">
                      <div id="clients-slider" class="pb-4 wow fadeIn">
                          <div class="text-center text-primary px-3 px-lg-4">
                              <img src="img/landing/cliente01.png" alt="" class="img-fluid mx-auto mb-2">
                              <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                              <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                          </div>
                          <div class="text-center text-primary px-3 px-lg-4">
                              <img src="img/landing/cliente02.png" alt="" class="img-fluid mx-auto mb-2">
                              <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                              <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                          </div>
                          <div class="text-center text-primary px-3 px-lg-4">
                              <img src="img/landing/cliente03.png" alt="" class="img-fluid mx-auto mb-2">
                              <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                              <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                          </div>
                          <div class="text-center text-primary px-3 px-lg-4">
                              <img src="img/landing/cliente01.png" alt="" class="img-fluid mx-auto mb-2">
                              <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                              <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                          </div>
                          <div class="text-center text-primary px-3 px-lg-4">
                              <img src="img/landing/cliente03.png" alt="" class="img-fluid mx-auto mb-2">
                              <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                              <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                          </div>
                          <div class="text-center text-primary px-3 px-lg-4">
                              <img src="img/landing/cliente02.png" alt="" class="img-fluid mx-auto mb-2">
                              <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                              <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </section>--}}
    </main>

@endsection
