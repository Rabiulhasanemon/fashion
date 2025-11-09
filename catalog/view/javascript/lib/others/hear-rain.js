app.onReady(window, "$", function () {
$(function () {
    (function () {
        var snowsrc = "https://www.startech.com.bd/love++.png"
        var no = 6;
        var hidesnowtime = 6;
        var dx, xp, yp;
        var am, stx, sty;
        var i, doc_width = 800, doc_height = 600;
        doc_width = self.innerWidth;
        doc_height = self.innerHeight;
        dx = [];
        xp = [];
        yp = [];
        am = [];
        stx = [];
        sty = [];
        function createHeart() {
            for (i = 0; i < no; ++i) {
                dx[i] = 0;
                xp[i] = Math.random() * (doc_width - 50);
                yp[i] = Math.random() * doc_height;
                am[i] = Math.random() * 20;
                stx[i] = 0.02 + Math.random() / 10;
                sty[i] = 1.7 + Math.random();
                $('body').append("<div id=\"dot" + i + "\" style=\"POSITION: absolute; Z-INDEX: " + i + "; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><img src='" + snowsrc + "' border=\"0\"><\/div>")
            }
        }

        function animate() {
            doc_width = window.innerWidth - 10;
            doc_height = window.innerHeight;
            for (i = 0; i < no; ++i) {
                yp[i] += sty[i];
                if (yp[i] > doc_height - 50) {
                    xp[i] = Math.random() * (doc_width - am[i] - 30);
                    yp[i] = 0;
                    stx[i] = 0.02 + Math.random() / 10;
                    sty[i] = 6.2 + Math.random();
                }
                dx[i] += stx[i];
                document.getElementById("dot" + i).style.top = yp[i] + "px";
                document.getElementById("dot" + i).style.left = xp[i] + am[i] * Math.sin(dx[i]) + "px";
            }
            snowtimer = setTimeout(animate, 20);
        }
        function hidesnow() {
            if (window.snowtimer)
                clearTimeout(snowtimer)
            for (i = 0; i < no; i++)
                document.getElementById("dot" + i).style.visibility = "hidden"
        }

        if (location.valGone !== true) {
            createHeart()
            animate();
            setTimeout(hidesnow, hidesnowtime * 1000);
        } else {
            location.valGone = true;
        }

    })();
})
}, 10);

