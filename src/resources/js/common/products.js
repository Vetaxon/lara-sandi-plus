import $ from 'jquery';

import {interval, fromEvent} from 'rxjs';
import {catchError, map, switchMap, takeUntil, takeWhile} from 'rxjs/operators';
import {fromFetch} from "rxjs/fetch";

$(window).on('load', function () {
    const btnUpload = document.getElementById('upload-products');
    if (btnUpload) {
        new Uploader(btnUpload).init()
    }

    $('#select-lang').on('change', function (e) {
        e.preventDefault();
        const search = window.location.search;
        const lang = e.target.value;
        window.location.href = window.location.host + (search.length ? search + '&lang=' + lang : '?lang=' + lang);
    })
});

const Uploader = function (btnUpload) {

    const uploadStatus = $('#upload-status');
    const uploaded = $('#uploaded');

    this.init = () => {
        clickSub().subscribe(data => {
            uploaded.text(data.uploaded);
            uploadStatus.text(data.status);
        })
    };

    const clickSub = () => {
        return fromEvent(btnUpload, 'click').pipe(
            map(e => {
                const target = $(e.target);
                target.attr('disabled', 'disabled');
                uploaded.text('');
                uploadStatus.text('');
                return target;
            }),
            switchMap(target => {
                return fromFetch(target.data('upload'))
            }),
            switchMap(response => response.json()),
            switchMap(response => {
                return interval(3000)
            }),
            switchMap(() => {
                return fromFetch($(btnUpload).data('check'))
            }),
            switchMap(response => response.json()),
            catchError(error => {
                $(btnUpload).removeAttr('disabled');
            }),
            map(res => res.data),
            takeWhile(data => {
                if (data.status) {
                    return true;
                } else {
                    window.location.reload();
                    return false;
                }
            })
        );
    };
};

