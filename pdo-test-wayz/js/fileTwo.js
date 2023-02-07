function importData() {

    let input = document.createElement('input');
    // input.setAttribute.name = "attachment";
    input.type = 'file';
    input.onchange = _ => {
      // you can use this method to get file and perform respective operations
              let files =   Array.from(input.files);
              console.log(files);
          };
    input.click();
    
  }