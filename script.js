document.getElementById('btn').addEventListener("click",()=> {
    const lines = document.querySelectorAll('.line');
    const data = [];

    lines.forEach((line) => {
        const tds = line.querySelectorAll('td');
        const tmp = [];

        tds.forEach((td)=>{
            tmp.push(td.textContent);
        })

        data.push(tmp);
    });

    fetch('export.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(response => response.blob() ).then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = "export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();
    }).catch(error => {console.log(error)});

});