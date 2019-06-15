const f12editmodal = {

    boundary: 'blob',

    backdropBlock: document.createElement('div'),

    editModalWrapper: document.createElement('div'),

    editModalBlock: document.createElement('div'),

    xhr: new XMLHttpRequest(),

    xhrScript: new XMLHttpRequest(),

    callbackActionUrl: '',

    executeScripts: function () {
        let inlineScript = "";
        const scripts = Array.prototype.slice.call(f12editmodal.editModalBlock.getElementsByTagName("script"));
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].src != "") {
                this.xhrScript.onload = function () {
                    if (f12editmodal.xhrScript.status >= 200 && f12editmodal.xhrScript.status < 300) {
                        eval(f12editmodal.xhrScript.responseText);
                    } else {
                        console.log('The script load failed!');
                        console.log(f12editmodal.xhrScript);
                    }
                };
                this.xhrScript.open('GET', scripts[i].src, false);
                this.xhrScript.send();
            } else {
                inlineScript += scripts[i].innerHTML;
            }
        }
        eval(inlineScript);
    },

    close: function (text) {
        if (text != null)
            info(text, 0);
        document.body.classList.remove('editmodal-modal-opened');
        setTimeout(function () {
            f12editmodal.backdropBlock.remove();
            f12editmodal.editModalWrapper.remove();
        }, 400);

    },

    expand: function () {
        if (f12editmodal.editModalBlock.classList.contains('expanded'))
            f12editmodal.editModalBlock.classList.remove('expanded')
        else
            f12editmodal.editModalBlock.classList.add('expanded')
    },

    open: function (callbackActionUrl, text) {

        if (text != null)
            info(text, 0);

        this.callbackActionUrl = callbackActionUrl;

        this.backdropBlock.setAttribute('class', 'editmodal-backdrop');
        this.editModalWrapper.setAttribute('class', 'editmodal-wrapper');
        this.editModalBlock.setAttribute('class', 'editmodal-modal');

        this.backdropBlock.addEventListener('click', () => {
            this.close();
        });

        document.body.appendChild(this.backdropBlock);
        this.editModalWrapper.appendChild(this.editModalBlock);
        document.body.appendChild(this.editModalWrapper);

        this.xhr.onload = function () {
            if (f12editmodal.xhr.status >= 200 && f12editmodal.xhr.status < 300) {
                f12editmodal.editModalBlock.innerHTML = f12editmodal.xhr.responseText
                f12editmodal.executeScripts();
                document.body.classList.add('editmodal-modal-opened');
            } else {
                console.log('The request failed!');
                console.log(f12editmodal.xhr);
            }
        };

        this.xhr.open('GET', this.callbackActionUrl, true);
        this.xhr.send();
    },

    submit: function () {
        submitButton = document.querySelector('button[type="submit"]');
        // submitButton.setAttribute('disabled', 'true');

        form = document.querySelector('div.editmodal-modal form');

        this.xhr.open('POST', form.action);
        this.xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + this.boundary);

        this.xhr.send(this.formatRequestBody(this.serializeForm(form)));
        // this.formatRequestBody(this.serializeForm(form));
        return false;
    },

    serializeForm: function (form) {
        return Array.from(new FormData(form)
            .entries())
            .reduce(function (response, current) {
                response[current[0]] = current[1];
                return response
            }, {})
    },

    formatRequestBody: function (object) {
        var data = '';
        for (var prop in object) {
            if (typeof (object[prop]) == 'string') {
                data += "--" + this.boundary + "\r\n";
                data += 'content-disposition: form-data; name="' + prop + '"\r\n';
                data += '\r\n';
                data += object[prop] + "\r\n";
            }

            // if (typeof (object[prop]) == 'object') {
            //     var file = object[prop];
            //     var fileFormElement = document.getElementsByName(prop);
            //     var reader = new FileReader();
            //
            //     file.binary = '';
            //
            //     reader.addEventListener("load", function () {
            //         file.binary = reader.result;
            //     });
            //
            //     reader.readAsBinaryString(file);
            //
            //     data += "--" + this.boundary + "\r\n";
            //     data += 'content-disposition: form-data; '
            //         + 'name="' + prop + '"; '
            //         + 'filename="' + file.name + '"\r\n';
            //     data += 'Content-Type: ' + file.type + '\r\n';
            //     data += '\r\n';
            //     data += file.binary + '\r\n';
            // }
        }
        data += "--" + this.boundary + "--";
        return data;
    },
}
