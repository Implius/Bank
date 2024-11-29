//Script qui permet d'envoyer les donnees vers un fichier php qui les convertira en CSV
//Si le bouton cliquer
document.getElementById('btn_csv').addEventListener("click",()=> {
    //On recupere les donner des lignes et initialise un tableau
    const lines = document.querySelectorAll('.line');
    const data = [];

    //On met les donnees recuperees dans un tableau que l'on met dans notre tableau du depart
    lines.forEach((line) => {
        const tds = line.querySelectorAll('td');
        const tmp = [];

        tds.forEach((td)=>{
            tmp.push(td.textContent);
        })

        data.push(tmp);
    });

    //On envoie les donnees au fichier export.php au format json
    //La methode then permet de lancer le telechargement du fichier cree par le fichier php
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
