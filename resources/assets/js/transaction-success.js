import $ from 'jquery';
window.$ = window.jQuery = $;

import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

$(function () {
    $('#__btn_toImage').click(function () {
        html2canvas(document.querySelector("#__receipt_to_image")).then(canvas => {
            // $('#__receipt_image_container').html('').append(canvas);
            // $("#__print_receipt_modal").modal("show");
            window.open(canvas.toDataURL("image/png"), '_blank');
        });
    });

    /*$('#__btn_toPdf').click(function () {
        let filename = 'TransactionReceipt.pdf';

        html2canvas(document.querySelector("#__receipt_to_image")).then(canvas => {
            let pdf = new jsPDF('p', 'mm', 'a4');
            pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 20, 211, 0);
            pdf.save(filename);
        });
    });*/
});