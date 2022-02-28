export const imageResizeSquare = function(imageData, maxSize, quality = 1, mimeType = 'image/jpeg') {

    return new Promise(function (resolve, reject) {

        let img = document.createElement('img');
        img.onload = function() {
            let canvas = document.createElement("canvas");

            // On adapte la taille du canvas
            let size;
            if (this.height > this.width) {
                size = this.width;
            }
            else {
                size = this.height;
            }
            canvas.width = size;
            canvas.height = size;

            let dx = 0;
            let dy = 0;
            if (this.width > this.height) {
                dx = -((this.width - this.height) / 2);
            }
            else if (this.width < this.height) {
                dy = -((this.height - this.width) / 2);
            }

            // Puis on convertit
            let context = canvas.getContext("2d");
            context.drawImage(this, dx, dy);

            let resizedImage = canvas.toDataURL(mimeType, quality);

            canvas.remove();
            img.remove();
            resolve(dataURLToBlob(resizedImage));
        };
        img.onerror = function() {
            canvas.remove();
            img.remove();
            reject(this);
        };
        img.src = imageData;
    });
}

export const imageResize = function(imageData, maxWidth, maxHeight, quality = 1, mimeType = 'image/jpeg') {

    return new Promise(function (resolve, reject) {

        var img = document.createElement('img');
        img.onload = function() {
            let canvas = document.createElement("canvas");
            // On détermine le ratio de scale
            if (!maxHeight) maxHeight = this.height;
            if (!maxWidth) maxWidth = this.width;
            let scale = Math.max(maxWidth/this.width, maxHeight/this.height);


            //On adapte la taille du canvas
            canvas.width = maxWidth;
            canvas.height = maxHeight;

            let dx = 0;
            let dy = 0;
            if (maxWidth < maxHeight) {
                dx = -(Math.abs(maxWidth - this.width) / 4);
            }
            else if (maxWidth > maxHeight) {
                dy = -(Math.abs(maxHeight - this.height) / 4);
            }

            // Puis on convertit
            let context = canvas.getContext("2d");
            context.scale( scale, scale);
            context.drawImage(this, dx, dy);

            let resizedImage = canvas.toDataURL(mimeType, quality);

            canvas.remove();
            img.remove();
            resolve(dataURLToBlob(resizedImage));
        };
        img.onerror = function() {
            canvas.remove();
            img.remove();
            reject(this);
        };
        img.src = imageData;
    });
}

export const dataURLToBlob = function(dataURL) {
    let BASE64_MARKER = ';base64,';
    let parts = null;
    let contentType = null;
    let raw = null;
    if (dataURL.indexOf(BASE64_MARKER) === -1) {
        parts = dataURL.split(',');
        contentType = parts[0].split(':')[1];
        raw = parts[1];

        return new Blob([raw], {type: contentType});
    }

    parts = dataURL.split(BASE64_MARKER);
    contentType = parts[0].split(':')[1];
    raw = window.atob(parts[1]);
    let rawLength = raw.length;

    let uInt8Array = new Uint8Array(rawLength);

    for (var i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], {type: contentType});
};
