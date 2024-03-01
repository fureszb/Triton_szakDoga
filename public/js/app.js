const wrapper = document.getElementById("signature-pad");
const clearButton = wrapper.querySelector("[data-action=clear]");
const undoButton = wrapper.querySelector("[data-action=undo]");
//const savePNGButton = wrapper.querySelector("[data-action=save-png]");
const saveSVGWithBackgroundButton = wrapper.querySelector("[data-action=save-svg-with-background]");
const canvas = wrapper.querySelector("canvas");
const signaturePad = new SignaturePad(canvas, {
  // It's Necessary to use an opaque color when saving image as JPEG;
  // this option can be omitted if only saving as PNG or SVG
  backgroundColor: 'rgb(255, 255, 255)'
});


// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
  // When zoomed out to less than 100%, for some very strange reason,
  // some browsers report devicePixelRatio as less than 1
  // and only part of the canvas is cleared then.
  const ratio = Math.max(window.devicePixelRatio || 1, 1);

  // This part causes the canvas to be cleared
  canvas.width = canvas.offsetWidth * ratio;
  canvas.height = canvas.offsetHeight * ratio;
  canvas.getContext("2d").scale(ratio, ratio);

  // This library does not listen for canvas changes, so after the canvas is automatically
  // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
  // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
  // that the state of this library is consistent with visual state of the canvas, you
  // have to clear it manually.
  //signaturePad.clear();

  // If you want to keep the drawing on resize instead of clearing it you can reset the data.
  signaturePad.fromData(signaturePad.toData());
}

window.onresize = resizeCanvas;
resizeCanvas();

function download(dataURL, filename) {
  const blob = dataURLToBlob(dataURL);
  const url = window.URL.createObjectURL(blob);

  const a = document.createElement("a");
  a.style = "display: none";
  a.href = url;
  a.download = filename;

  document.body.appendChild(a);
  a.click();

  window.URL.revokeObjectURL(url);
}

function dataURLToBlob(dataURL) {
  const parts = dataURL.split(';base64,');
  const contentType = parts[0].split(":")[1];
  const raw = window.atob(parts[1]);
  const rawLength = raw.length;
  const uInt8Array = new Uint8Array(rawLength);

  for (let i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], { type: contentType });
}

clearButton.addEventListener("click", () => {
  signaturePad.clear();
});

undoButton.addEventListener("click", () => {
  const data = signaturePad.toData();

  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

// app.js
document.addEventListener('DOMContentLoaded', function () {
const savePNGButton = document.querySelector("[data-action=save-png]");
//const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
console.log('savePNGButton megtalálva', savePNGButton);
savePNGButton.addEventListener("click", () => {
  console.log('savePNGButton kattint', savePNGButton);
    if (signaturePad.isEmpty()) {
      alert("Kérlek írd alá mentés előtt!");
      event.preventDefault()
    } else {
        //window.location.href = '/megrendeles/saveImage'
        const dataURL = signaturePad.toDataURL();
        saveImage(dataURL);
    }
  });
});

function saveImage(dataURL) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/save-image', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ dataURL: dataURL })
      })
        .then(response => {
          if (response.ok) {
            console.log('Kép mente');
            // Sikerüzenet megjelenítése
            //alert('Az aláírás és az ügyfél sikeresen mentve lett!');
            // 1 másodperc várakozás után átirányítás
            setTimeout(() => {
              window.location.href = '/send-mail';
            }, 1000);
          } else {
            console.error('Error saving image:', response.statusText);
          }
        })
        .catch(error => {
          console.error('Error saving image:', error);
        });
    }



