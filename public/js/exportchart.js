$('#btnreporte').click(function(event) {
     var reportPageHeight = $('#reportPage').innerHeight();
     var reportPageWidth = $('#reportPage').innerWidth();
      var pdfCanvas = $('<canvas />').attr({
         id: "canvaspdf",
         width: reportPageWidth,
         height: reportPageHeight
     });
     var pdfctx = $(pdfCanvas)[0].getContext('2d');
     var pdfctxX = 0;
     var pdfctxY = 0;
     var buffer = 100;
     $("canvas").each(function(index) {
         var canvasHeight = $(this).innerHeight();
         var canvasWidth = $(this).innerWidth();
         // draw the chart into the new canvas
         pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
         pdfctxX += canvasWidth + buffer;
         // our report page is in a grid pattern so replicate that in the new canvas
         if (index % 2 === 1) {
             pdfctxX = 0;
             pdfctxY += canvasHeight + buffer;
         }
     });
     var pdf = new jsPDF('l', 'pt', [reportPageWidth, reportPageHeight]);
     pdf.addImage($(pdfCanvas)[0], 'PNG', 0, 0);
     pdf.save('filename.pdf');
 });