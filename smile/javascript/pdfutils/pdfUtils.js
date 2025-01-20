
const pdfUtils = {
  getImgDimens: function (url){
    return new Promise((resolve, reject) => {
      let img = new Image()
      img.onload = () => resolve({ width: img.width, height: img.height })
      img.onerror = reject
      img.src = url
    })
  },
  fetchBuffer: async function (url) {
    return await fetch(url)
      .then(res => {
        const contentType  = res.headers.get('content-type') || '';
        switch (contentType) {
          case 'image/jpeg':
          case 'image/jpg':
          case 'image/png':
          case 'application/pdf':
            return res.arrayBuffer().then(buffer => {
              return {
                ret: '1',
                msg: 'Sukses',
                buffer: buffer
              };
            });
          case 'application/json':
            return res.json().then(data => {
              return data;
            });
          default:
            return {
              ret: '-1',
              msg: 'Content Type tidak didukung'
            }
        }
      })
      .catch(err => {
        return {
          ret : '-1', 
          msg: 'Gagal mengambil file'
        }
      });
  },
  bufferPdfDoc: async function (pdfDoc) {
    let pdfBuffer = Buffer.from(pdfDoc.buffer, 'binary');
    return pdfBuffer;
  },
  img2pdf: async function (url) {
    const fontSize     = 12;
    const leftMargin   = 40;
    const rightMargin  = 40;
    const topMargin    = 40;
    const bottomMargin = 40;
    
    const resBuffer = await pdfUtils.fetchBuffer(url);
    
    if (resBuffer.ret !== '1') {
      const pdfDoc = await PDFDocument.create();
      pdfDoc.setProducer('');
      pdfDoc.setCreator('');

      const helveticaFont = await pdfDoc.embedFont(StandardFonts.Helvetica);
      const page          = pdfDoc.addPage();
      const xPosition     = leftMargin;
      const yPosition     = page.getHeight() - (topMargin + fontSize);

      page.drawText(`Dokumen image tidak ditemukan!`, {
        x: xPosition,
        y: yPosition,
        size: fontSize,
        font: helveticaFont,
        color: rgb(0, 0, 0),
      });

      return await pdfDoc.save();
    } else {
      const pdfDoc = await PDFDocument.create();
      pdfDoc.setProducer('');
      pdfDoc.setCreator('');
      const page   = pdfDoc.addPage();
      
      let dimens = {};
      await pdfUtils.getImgDimens(url).then(dims => {
        dimens = dims
      })
      
      const buffer     = resBuffer.buffer;
      const dimensions = dimens;
      const jpgImage   = await pdfDoc.embedJpg(buffer)

      const imageWidth   = dimensions.width;
      const imageHeight  = dimensions.height;
      const maxWidth     = page.getWidth() - (leftMargin + rightMargin);
      const maxHeight    = page.getHeight() - (topMargin + bottomMargin);
      const persenWidth  = Math.floor(maxWidth / imageWidth * 100);
      const persenHeight = Math.floor(maxHeight / imageHeight * 100);
      const fitPersen    = persenWidth <= persenHeight ? persenWidth : persenHeight;
      const fitWidth     = imageWidth * fitPersen / 100;
      const fitHeight    = imageHeight * fitPersen / 100;
      const xPosition    = leftMargin;
      const yPosition    = page.getHeight() - (topMargin + fitHeight);
      
      page.drawImage(jpgImage, {
        x: xPosition,
        y: yPosition,
        width: fitWidth,
        height: fitHeight,
      });

      return await pdfDoc.save();
    }
  },
  url2pdf: async function (url) {
    const fontSize     = 12;
    const leftMargin   = 40;
    const rightMargin  = 40;
    const topMargin    = 40;
    const bottomMargin = 40;

    const resBuffer = await pdfUtils.fetchBuffer(url);
    
    if (resBuffer.ret !== '1') {
      const pdfDoc = await PDFDocument.create();
      pdfDoc.setProducer('');
      pdfDoc.setCreator('');

      const helveticaFont = await pdfDoc.embedFont(StandardFonts.Helvetica);
      const page          = pdfDoc.addPage();
      const xPosition     = leftMargin;
      const yPosition     = page.getHeight() - (topMargin + fontSize);
      page.drawText(`Dokumen image tidak ditemukan!`, {
        x: xPosition,
        y: yPosition,
        size: fontSize,
        font: helveticaFont,
        color: rgb(0, 0, 0),
      });

      return await pdfDoc.save();
    } else {
      const pdfDoc = await PDFDocument.load(resBuffer.buffer);

      return await pdfDoc.save();
    }
  },
  appendHeaderFooter: async function (pdfDocBuffer, headerText, footerText) {
    const newPdfDoc = await PDFDocument.create(PDFLib.PageSizes.A4);
    newPdfDoc.setProducer('');
    newPdfDoc.setCreator('');

    const pdfDoc    = await PDFDocument.load(pdfDocBuffer);

    const fontType     = await newPdfDoc.embedFont(StandardFonts.Helvetica);
    const fontSize     = 9;
    const leftMargin   = 8;
    const rightMargin  = 8;
    const topMargin    = 16;
    const bottomMargin = 16;
    
    if (headerText.length == 0 && footerText.length == 0) {
      return pdfdoc;
    }
    
    const pages = pdfDoc.getPages();
    for (let page of pages) {
      const cropBox = page.getCropBox() || page.getMediaBox();

      const [widthPage, heightPage] = PDFLib.PageSizes.A4;

      const widthCrop  = cropBox.width;
      const heightCrop = cropBox.height;
      
      let heightHeader = fontSize + (topMargin * 2);
      let heightFooter = fontSize + (bottomMargin * 2);
      
      heightHeader = headerText.length == 0 ? 0 : heightHeader;
      heightFooter = footerText.length == 0 ? 0 : heightFooter;
      
      const heightPageFit   = heightCrop - (heightHeader + heightFooter);
      const fitScale        = heightPageFit / heightPage;

      const preamble = await newPdfDoc.embedPage(page);
      const preambleDims = preamble.scale(fitScale);

      let xPos = widthPage / 2 - preambleDims.width / 2;
      let yPos = heightPage / 2 - preambleDims.height / 2;

      const newPage = newPdfDoc.addPage(); 
      newPage.drawPage(preamble, {
        ...preambleDims,
        x: xPos,
        y: yPos,
      });

      xPos = leftMargin;
      yPos = (heightPage - topMargin);
      
      newPage.drawText(headerText, {
        x: xPos,
        y: yPos,
        size: fontSize,
        font: fontType,
        color: rgb(0, 0, 0),
      });

      xPos = leftMargin;
      yPos = bottomMargin;

      newPage.drawText(footerText, {
        x: xPos,
        y: yPos,
        size: fontSize,
        font: fontType,
        color: rgb(0, 0, 0),
      });
    } 

    return await newPdfDoc.save();
  },
  mergePdf: async function (dataDocs) {
    const mergedPdf = await PDFDocument.create();
    mergedPdf.setProducer('');
    mergedPdf.setCreator('');

    for (let data of dataDocs) {
      let docPdf = null;
      let headerText = `File ${data.namaDokumen} : ${data.pemilik}`;
      let footerText = `${data.infoCetak}`;
      switch (data.mimeType) {
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/png':
          docPdf = await pdfUtils.img2pdf(data.urlDokumen);
          docPdf = await pdfUtils.appendHeaderFooter(docPdf, headerText, footerText);
          break;
        case 'application/pdf':
          docPdf = await pdfUtils.url2pdf(data.urlDokumen);
          docPdf = await pdfUtils.appendHeaderFooter(docPdf, headerText, footerText);
          break;
        default:
          docPdf = null;
          break;
      }
      if (docPdf !== null) {
        docPdf = await PDFDocument.load(docPdf);
        const copiedPages = await mergedPdf.copyPages(docPdf, docPdf.getPageIndices());
        copiedPages.forEach((page) => mergedPdf.addPage(page));
      }
    }
    
    return await mergedPdf.save();
  }
}