<script src="../tools-images.js"></script>
<style lang="scss" src="../../sass/styleSaleUsdVen.scss"></style>

<template>
    <div class="container">
        <form>
            <div class="row">
                <div class="col">
                    <h2>Operaciones generales Venezuela</h2>
                </div>
            </div>
            <div class="row">
                <div class="col" style="background: #f2f2f2; padding-top: 1rem; padding-bottom: 1rem;">
                    <div class="__venezuelan_offers_list">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="text-info">Venta de USD Venezuela</h4>
                            </div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="vzlaSaleUSD_cost">Costo:</label>
                                        <input type="number"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_cost"
                                               class="form-control"
                                               id="vzlaSaleUSD_cost"
                                               @change="calculatePrices"
                                               aria-describedby="vzlaSaleUSD_costHelp">
                                        <small id="vzlaSaleUSD_costHelp" class="form-text text-muted">
                                            Costo del dólar
                                        </small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="vzlaSaleUSD_instantPrice">Precio Inmediato:</label>
                                        <input type="number"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_instantPrice"
                                               class="form-control"
                                               id="vzlaSaleUSD_instantPrice"
                                               aria-describedby="vzlaSaleUSD_instantPriceHelp">
                                        <small id="vzlaSaleUSD_instantPriceHelp" class="form-text text-muted">
                                            Precio entrega inmediata
                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="vzlaSaleUSD_72Price">Precio 72 horas:</label>
                                        <input type="number"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_72Price"
                                               class="form-control"
                                               id="vzlaSaleUSD_72Price"
                                               aria-describedby="vzlaSaleUSD_72PriceHelp">
                                        <small id="vzlaSaleUSD_72PriceHelp" class="form-text text-muted">
                                            Precio entrega 72 horas
                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="vzlaSaleUSD_7dPrice">Precio 7 días:</label>
                                        <input type="number"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_7dPrice"
                                               class="form-control"
                                               id="vzlaSaleUSD_7dPrice"
                                               aria-describedby="vzlaSaleUSD_7dPriceHelp">
                                        <small id="vzlaSaleUSD_7dPriceHelp" class="form-text text-muted">
                                            Precio entrega 7 días
                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="vzlaSaleUSD_15dPrice">Precio 15 días:</label>
                                        <input type="number"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_15dPrice"
                                               class="form-control"
                                               id="vzlaSaleUSD_15dPrice"
                                               aria-describedby="vzlaSaleUSD_15dPriceHelp">
                                        <small id="vzlaSaleUSD_15dPriceHelp" class="form-text text-muted">
                                            Precio entrega 15 días
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="vzlaSaleUSD_dateTime">Fecha y Hora:</label>
                                        <input type="datetime-local"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_dateTime"
                                               class="form-control"
                                               id="vzlaSaleUSD_dateTime"
                                               aria-describedby="vzlaSaleUSD_dateTimeHelp">
                                        <small id="vzlaSaleUSD_dateTimeHelp" class="form-text text-muted">
                                            Fecha y hora de la oferta
                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="vzlaSaleUSD_saleUsdBank">Banco:</label>
                                        <select class="form-control"
                                                id="vzlaSaleUSD_saleUsdBank"
                                                aria-describedby="vzlaSaleUSD_saleUsdBankHelp">
                                            <option>Seleccione</option>
                                            <option :selected="VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank === 'banesco'"
                                                    value="banesco">Banesco
                                            </option>
                                            <option :selected="VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank === 'mercantil'"
                                                    value="mercantil">Mercantil
                                            </option>
                                            <option :selected="VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank === 'venezuela'"
                                                    value="venezuela">Banco de Venezuela
                                            </option>
                                        </select>
                                        <small id="vzlaSaleUSD_saleUsdBankHelp" class="form-text text-muted">
                                            Banco de la oferta
                                        </small>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="vzlaSaleUSD_tDescription">Tasa (descripción):</label>
                                        <input type="text"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_tDescription"
                                               class="form-control"
                                               id="vzlaSaleUSD_tDescription"
                                               aria-describedby="vzlaSaleUSD_tDescriptionHelp">
                                        <small id="vzlaSaleUSD_tDescriptionHelp" class="form-text text-muted">
                                            Descripción de la tasa. Ejem. "Tasa para 100 máximo."
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="vzlaSaleUSD_validity">Validaciones (descripción):</label>
                                        <input type="text"
                                               :value="VnzlaSaleUSD.vzlaSaleUSD_validity"
                                               class="form-control"
                                               id="vzlaSaleUSD_validity"
                                               aria-describedby="vzlaSaleUSD_validityHelp">
                                        <small id="vzlaSaleUSD_validityHelp" class="form-text text-muted">
                                            Validaciones. Ejem. Válido sólo por 1 hora.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="padding-top: 2rem">
                                <a href="javascript:void(0);" @click="saveVzlaSaleUSD" class="btn btn-primary">Guardar /
                                    Generar</a>
                            </div>
                        </div>
                        <div id="saleUsdModal" class="white-popup mfp-hide saleUsdModal">
                            <div id="saleUsdImage"></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="vzlaSaleUSD_image" class="vzlaSaleUSD_image">
                                    <div class="saleCanvas">
                                        <div class="head-1">
                                            <h3>Venta
                                                <img src="/img/tools/images-export/icon-venta.png" alt="Icono Venta">
                                            </h3>
                                            <div>
                                                <div class="date">
                                                    <span class="month">
                                                        {{VnzlaSaleUSD.vzlaSaleUSD_dateHuman.month}}
                                                    </span>
                                                    <span class="n_day">
                                                        {{VnzlaSaleUSD.vzlaSaleUSD_dateHuman.day}}
                                                    </span>
                                                    <span class="n_year">
                                                        {{VnzlaSaleUSD.vzlaSaleUSD_dateHuman.year}}
                                                    </span>
                                                </div>
                                                <div class="hour">
                                                    <span class="n_hour">
                                                        {{VnzlaSaleUSD.vzlaSaleUSD_dateHuman.hour}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="max-bank">
                                                        <div class="max"
                                                             v-if="VnzlaSaleUSD.vzlaSaleUSD_tDescription !== '0'">
                                                            <p>
                                                                {{VnzlaSaleUSD.vzlaSaleUSD_tDescription}}
                                                            </p>
                                                        </div>
                                                        <div class="bank">
                                                            <img src="/img/tools/images-export/mercantil-logo.png"
                                                                 v-if="VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank === 'mercantil'"
                                                                 alt="">
                                                            <img src="/img/tools/images-export/banesco-logo.png"
                                                                 v-if="VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank === 'banesco'"
                                                                 alt="">
                                                            <img src="/img/tools/images-export/banco_venezuela-logo.png"
                                                                 v-if="VnzlaSaleUSD.vzlaSaleUSD_saleUsdBank === 'venezuela'"
                                                                 alt="">
                                                        </div>
                                                    </div>
                                                    <div class="instant"
                                                         v-if="VnzlaSaleUSD.vzlaSaleUSD_instantPrice !== '' &&
                                                         VnzlaSaleUSD.vzlaSaleUSD_instantPrice !== 0 &&
                                                         VnzlaSaleUSD.vzlaSaleUSD_instantPrice !== '0'">
                                                        <h4>ENTREGA INMEDIATA</h4>
                                                        <span class="money">
                                                            <span class="small">Bs.S</span>
                                                            {{VnzlaSaleUSD.vzlaSaleUSD_instantPrice}}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="72 shipment">
                                                        <div class="background">
                                                            <h4>ENTREGA A 72 HORAS</h4>
                                                            <span class="money">
                                                                <span class="small">Bs.S</span>
                                                            {{VnzlaSaleUSD.vzlaSaleUSD_72Price}}
                                                            </span>
                                                        </div>
                                                        <span class="helper">Entregando dolares</span>
                                                    </div>
                                                    <div class="7d shipment">
                                                        <div class="background">
                                                            <h4>ENTREGA A 7 DÍAS HÁBILES</h4>
                                                            <span class="money">
                                                                <span class="small">Bs.S</span>
                                                            {{VnzlaSaleUSD.vzlaSaleUSD_7dPrice}}
                                                            </span>
                                                        </div>
                                                        <span class="helper">Entregando dolares</span>
                                                    </div>
                                                    <div class="15d shipment">
                                                        <div class="background">
                                                            <h4>ENTREGA A 15 DÍAS HÁBILES</h4>
                                                            <span class="money">
                                                                <span class="small">Bs.S</span>
                                                            {{VnzlaSaleUSD.vzlaSaleUSD_15dPrice}}
                                                            </span>
                                                        </div>
                                                        <span class="helper">Entregando dolares</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-7">
                                                    <p class="available"><br> <!-- Disponibilidad en cualquier entidad
                                                        bancaria --></p>
                                                </div>
                                                <div class="col-sm-5">
                                                    <p class="validity"
                                                       v-if="VnzlaSaleUSD.vzlaSaleUSD_validity !== '0'">
                                                        {{VnzlaSaleUSD.vzlaSaleUSD_validity}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer">
                                            <ul class="nav nav-fill">
                                                <li class="nav-item">
                                                    <img src="/img/tools/images-export/icon-facebook.png" alt="">
                                                    facebook.com/coinbankusa
                                                </li>
                                                <li class="nav-item">
                                                    <img src="/img/tools/images-export/icon-instagram.png" alt="">
                                                    instagram.com/coinbankusa
                                                </li>
                                                <li class="nav-item">
                                                    <img src="/img/tools/images-export/icon-twitter.png" alt="">
                                                    twitter.com/coinbankusa
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form>
            <div class="row">
                <div class="col" style="background: #f2f2f2; padding-top: 1rem; padding-bottom: 1rem;">
                    <div class="__venezuelan_offers_list">
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="text-info">Compra de USD Venezuela</h4>
                            </div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_from1">Desde (1):</label>
                                        <input type="number"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_from1"
                                               class="form-control"
                                               id="vzlaBuyUSD_from1"
                                               aria-describedby="vzlaBuyUSD_from1Help">
                                        <small id="vzlaBuyUSD_from1Help" class="form-text text-muted">
                                            Monto mínimo inicial (1)
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_to1">Hasta (1):</label>
                                        <input type="number"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_to1"
                                               class="form-control"
                                               id="vzlaBuyUSD_to1"
                                               aria-describedby="vzlaBuyUSD_to1Help">
                                        <small id="vzlaBuyUSD_to1Help" class="form-text text-muted">
                                            Monto máximo (1)
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_price1">Precio (1):</label>
                                        <input type="number"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_price1"
                                               class="form-control"
                                               id="vzlaBuyUSD_price1"
                                               aria-describedby="vzlaBuyUSD_price1Help">
                                        <small id="vzlaBuyUSD_price1Help" class="form-text text-muted">
                                            Precio de compra (1)
                                        </small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_from2">Desde (2):</label>
                                        <input type="number"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_from2"
                                               class="form-control"
                                               id="vzlaBuyUSD_from2"
                                               aria-describedby="vzlaBuyUSD_from2Help">
                                        <small id="vzlaBuyUSD_from2Help" class="form-text text-muted">
                                            Monto mínimo inicial (2)
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_to2">Hasta (2):</label>
                                        <input type="number"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_to2"
                                               class="form-control"
                                               id="vzlaBuyUSD_to2"
                                               aria-describedby="vzlaBuyUSD_to2Help">
                                        <small id="vzlaBuyUSD_to2Help" class="form-text text-muted">
                                            Monto máximo (2)
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_price2">Precio (2):</label>
                                        <input type="number"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_price2"
                                               class="form-control"
                                               id="vzlaBuyUSD_price2"
                                               aria-describedby="vzlaBuyUSD_price2Help">
                                        <small id="vzlaBuyUSD_price2Help" class="form-text text-muted">
                                            Precio de compra (2)
                                        </small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="vzlaBuyUSD_buyUsdDateTime">Fecha y Hora:</label>
                                        <input type="datetime-local"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_buyUsdDateTime"
                                               class="form-control"
                                               id="vzlaBuyUSD_buyUsdDateTime"
                                               aria-describedby="vzlaBuyUSD_buyUsdDateTimeHelp">
                                        <small id="vzlaBuyUSD_buyUsdDateTimeHelp" class="form-text text-muted">
                                            Fecha y hora de la oferta
                                        </small>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="vzlaBuyUSD_tWarning">Advertencia (descripción):</label>
                                        <input type="text"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_tWarning"
                                               class="form-control"
                                               id="vzlaBuyUSD_tWarning"
                                               aria-describedby="vzlaBuyUSD_tWarningHelp">
                                        <small id="vzlaBuyUSD_tWarningHelp" class="form-text text-muted">
                                            Descripción de la tasa. Ejem.
                                            "Montos superiores a $1000 preguntar tasa al momento."
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="vzlaBuyUSD_buyValidity">Validaciones (descripción):</label>
                                        <input type="text"
                                               :value="VnzlaBuyUSD.vzlaBuyUSD_buyValidity"
                                               class="form-control"
                                               id="vzlaBuyUSD_buyValidity"
                                               aria-describedby="vzlaBuyUSD_buyValidityHelp">
                                        <small id="vzlaBuyUSD_buyValidityHelp" class="form-text text-muted">
                                            Validaciones. Ejem. Válido sólo por 1 hora.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="padding-top: 2rem">
                                <a href="javascript:void(0);" @click="saveVzlaBuyUSD" class="btn btn-primary">Guardar /
                                    Generar</a>
                            </div>
                        </div>
                        <div id="buyUsdModal" class="white-popup mfp-hide buyUsdModal">
                            <div id="buyUsdImage"></div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="vzlaBuyUSD_image" class="vzlaBuyUSD_image">
                                    <div class="saleCanvas">
                                        <div class="head-1">
                                            <h3>Compra
                                                <img src="/img/tools/images-export/icon-compra.png" alt="">
                                            </h3>
                                            <div>
                                                <div class="date">
                                                    <span class="month">
                                                        {{VnzlaBuyUSD.vzlaBuyUSD_dateHuman.month}}
                                                    </span>
                                                    <span class="n_day">
                                                        {{VnzlaBuyUSD.vzlaBuyUSD_dateHuman.day}}
                                                    </span>
                                                    <span class="n_year">
                                                        {{VnzlaBuyUSD.vzlaBuyUSD_dateHuman.year}}
                                                    </span>
                                                </div>
                                                <div class="hour">
                                                    <span class="n_hour">
                                                        {{VnzlaBuyUSD.vzlaBuyUSD_dateHuman.hour}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="max-1-2">
                                                        <div class="max max-1">
                                                            <p>
                                                                DESDE
                                                                <br>
                                                                <span class="amount">
                                                                    ${{VnzlaBuyUSD.vzlaBuyUSD_from1}}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="max max-2">
                                                            <p>
                                                                HASTA
                                                                <br>
                                                                <span class="amount">
                                                                    ${{VnzlaBuyUSD.vzlaBuyUSD_to1}}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="max-3-4">
                                                        <div class="max max-3">
                                                            <p>
                                                                DESDE
                                                                <br>
                                                                <span class="amount">
                                                                    ${{VnzlaBuyUSD.vzlaBuyUSD_from2}}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="max max-4">
                                                            <p>
                                                                HASTA
                                                                <br>
                                                                <span class="amount">
                                                                    ${{VnzlaBuyUSD.vzlaBuyUSD_to2}}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="72 shipment">
                                                        <div class="background">
                                                            <span class="money">
                                                                <span class="small">Bs.S</span>
                                                                {{VnzlaBuyUSD.vzlaBuyUSD_price1}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="7d shipment">
                                                        <div class="background">
                                                            <span class="money">
                                                                <span class="small">Bs.S</span>
                                                                {{VnzlaBuyUSD.vzlaBuyUSD_price2}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <p class="warning"
                                                       v-if="VnzlaBuyUSD.vzlaBuyUSD_tWarning !== '0'">
                                                        {{VnzlaBuyUSD.vzlaBuyUSD_tWarning}}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-7">
                                                    <p class="available"><br> <!-- Disponibilidad en cualquier entidad
                                                        bancaria --></p>
                                                </div>
                                                <div class="col-sm-5">
                                                    <p class="validity"
                                                       v-if="VnzlaBuyUSD.vzlaBuyUSD_buyValidity !== '0'">
                                                        {{VnzlaBuyUSD.vzlaBuyUSD_buyValidity}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer">
                                            <ul class="nav nav-fill">
                                                <li class="nav-item">
                                                    <img src="/img/tools/images-export/icon-facebook.png" alt="">
                                                    facebook.com/coinbankusa
                                                </li>
                                                <li class="nav-item">
                                                    <img src="/img/tools/images-export/icon-instagram.png" alt="">
                                                    instagram.com/coinbankusa
                                                </li>
                                                <li class="nav-item">
                                                    <img src="/img/tools/images-export/icon-twitter.png" alt="">
                                                    twitter.com/coinbankusa
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </form>

        <form>
            <div class="row">
                <div class="col">
                    <h2>Operaciones en otros países</h2>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="selectCountry">Seleciona un país</label>
                        <br>
                        <select id="selectCountry"
                                @change="addCountry"
                                aria-describedby="selectCountryHelp">
                            <option>Selecciona alguno de la lista</option>
                            <option v-for="country in countries" v-if="country.enabled === 1" :value="country.iso">
                                {{country.name}}
                            </option>
                        </select>
                        <small id="selectCountryHelp" class="form-text text-muted">
                            Puedes agregar luego múltiples ofertas para un mismo país.
                        </small>
                    </div>
                </div>
            </div>
            <div class="row __country_container" v-for="(country, index) in activeCountries">
                <div class="col"
                     style="background: #f2f2f2; padding-top: 1rem; padding-bottom: 1rem; margin-bottom: 2rem">
                    <h3>
                        {{country.name}} -
                        <a href="javascript:void(0);"
                           :data-country_slug="index"
                           @click="removeCountry"
                           class="btn btn-danger">Quitar país</a>
                    </h3>
                    <div class="__offers_list">
                        <div class="row __offer_container"
                             style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #333"
                             v-for="(offer, offerIndex) in country.offers"
                             :data-parent_country="index">
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label :for="index + '-type-' + offerIndex">Tipo:</label>
                                        <select class="form-control"
                                                :id="index + '-type-' + offerIndex"
                                                :aria-describedby="index + '-typeHelp-' + offerIndex">
                                            <option :selected="offer.type === '1'"
                                                    value="1">Envío de remesas a Venezuela
                                            </option>
                                            <option :selected="offer.type === '2'"
                                                    value="2">Envío de remesas al país
                                            </option>
                                            <option :selected="offer.type === '3'"
                                                    value="3">Venta de USD
                                            </option>
                                            <option :selected="offer.type === '4'"
                                                    value="4">Venta de BTC
                                            </option>
                                        </select>
                                        <small :id="index + '-typeHelp-' + offerIndex" class="form-text text-muted">
                                            Tipo de oferta para la imagen.
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <label :for="index + '-msrp-' + offerIndex">Tasa de cambio:</label>
                                        <input type="number"
                                               class="form-control"
                                               :id="index + '-msrp-' + offerIndex"
                                               :value="offer.msrp"
                                               :aria-describedby="index + '-msrpHelp-' + offerIndex">
                                        <small :id="index + '-msrpHelp-' + offerIndex" class="form-text text-muted">
                                            Tasa de cambio a aplicar.
                                        </small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label :for="index + '-dateTime-' + offerIndex">Fecha y Hora:</label>
                                        <input type="datetime-local"
                                               class="form-control"
                                               :id="index + '-dateTime-' + offerIndex"
                                               :value="offer.dateTime"
                                               :aria-describedby="index + '-dateTimeHelp-' + offerIndex">
                                        <small :id="index + '-dateTimeHelp-' + offerIndex" class="form-text text-muted">
                                            Fecha y hora de la oferta
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <label :for="index + '-validity-' + offerIndex">Validaciones
                                            (descripción):</label>
                                        <input type="text"
                                               :value="offer.validity"
                                               class="form-control"
                                               :id="index + '-validity-' + offerIndex"
                                               :aria-describedby="index + '-validityHelp-' + offerIndex">
                                        <small :id="index + '-validityHelp-' + offerIndex" class="form-text text-muted">
                                            Validaciones. Ejem. Válido sólo por 1 hora.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="padding-top: 2rem">
                                <a href="javascript:void(0);"
                                   :data-offer_index="offerIndex"
                                   :data-offer_country="index"
                                   @click="saveForeignOffer"
                                   class="btn btn-primary">
                                    Guardar / Generar
                                </a>
                                <br><br>
                                <a href="javascript:void(0);"
                                   :data-offer_index="offerIndex"
                                   :data-offer_country="index"
                                   @click="removeForeignOffer"
                                   class="btn btn-danger">
                                    Quitar
                                </a>
                            </div>
                            <div class="col-md-12">
                                <div :id="index + '-modal-' + offerIndex"
                                     class="white-popup mfp-hide foreignOfferModal">
                                    <div :id="index + '-modalImage-' + offerIndex"></div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div :id="index + '-domImage-' + offerIndex" class="foreignDomImage">
                                            <div class="saleCanvas">
                                                <div class="head-1">
                                                    <h3>
                                                        Tasa de cambio
                                                    </h3>
                                                    <div>
                                                        <div class="date">
                                                            <span class="month">
                                                                {{offer.dateHuman.month}}
                                                            </span>
                                                            <span class="n_day">
                                                                {{offer.dateHuman.day}}
                                                            </span>
                                                            <span class="n_year">
                                                                {{offer.dateHuman.year}}
                                                            </span>
                                                        </div>
                                                        <div class="hour">
                                                            <span class="n_hour">
                                                                {{offer.dateHuman.hour}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="information">
                                                                <span class="money">
                                                                    {{offer.msrp}}
                                                                    <span class="symbol" v-if="offer.type === '1'">
                                                                        VES
                                                                    </span>
                                                                    <span class="symbol" v-if="offer.type === '2' ||
                                                                    offer.type === '4'">
                                                                        {{offer.countryCurr}}
                                                                    </span>
                                                                    <span class="symbol" v-if="offer.type === '3'">
                                                                        USD
                                                                    </span>
                                                                </span>
                                                                <span class="origin" v-if="offer.type === '1' ||
                                                                offer.type === '3'">
                                                                    Por cada {{offer.humanCurr}}
                                                                </span>
                                                                <span class="origin" v-if="offer.type === '4'">
                                                                    Precio BTC
                                                                    Precio BTC
                                                                </span>
                                                                <span class="origin" v-if="offer.type === '2'">
                                                                    Por cada Dólar
                                                                </span>
                                                                <img v-if="index === 'co'"
                                                                     src="/img/tools/images-export/flag-co.png" alt="">
                                                                <img v-if="index === 'pe'"
                                                                     src="/img/tools/images-export/flag-pe.png" alt="">
                                                                <img v-if="index === 'us'"
                                                                     src="/img/tools/images-export/flag-us.png" alt="">
                                                                <img v-if="index === 'pt'"
                                                                     src="/img/tools/images-export/flag-pt.png" alt="">
                                                                <img v-if="index === 'es'"
                                                                     src="/img/tools/images-export/flag-es.png" alt="">
                                                                <img v-if="index === 'pa'"
                                                                     src="/img/tools/images-export/flag-pa.png" alt="">
                                                                <img v-if="index === 'br'"
                                                                     src="/img/tools/images-export/flag-br.png" alt="">
                                                                <img v-if="index === 'ar'"
                                                                     src="/img/tools/images-export/flag-ar.png" alt="">
                                                                <img v-if="index === 'cl'"
                                                                     src="/img/tools/images-export/flag-cl.png" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-7">
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <p class="validity">{{offer.validity}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="footer">
                                                    <ul class="nav nav-fill">
                                                        <li class="nav-item">
                                                            <img src="/img/tools/images-export/icon-facebook.png"
                                                                 alt="">
                                                            facebook.com/coinbankusa
                                                        </li>
                                                        <li class="nav-item">
                                                            <img src="/img/tools/images-export/icon-instagram.png"
                                                                 alt="">
                                                            instagram.com/coinbankusa
                                                        </li>
                                                        <li class="nav-item">
                                                            <img src="/img/tools/images-export/icon-twitter.png" alt="">
                                                            twitter.com/coinbankusa
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="__add_offer">
                        <a href="javascript:void(0);"
                           class="btn btn-primary"
                           @click="addNewOffer"
                           :data-country="index">
                            Agregar nueva oferta
                        </a>
                    </div>
                </div>
            </div>
            <hr>
        </form>
    </div>
</template>