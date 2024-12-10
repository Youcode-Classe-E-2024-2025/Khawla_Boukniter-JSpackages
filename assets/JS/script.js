const authBtn = document.querySelector('input[name="add_author"]');
authBtn.addEventListener('click', function() {
    document.querySelector('.auteur-fields').style = "display: block";
});

const packBtn = document.querySelector('input[name="add_package"]');
packBtn.addEventListener('click', function() {
    document.querySelector('.package-fields').style = "display: block";
});

const versionBtn = document.querySelector('input[name="add_version"]');
versionBtn.addEventListener('click', function() {
    document.querySelector('.version-fields').style = "display: block";
});

const packageList = document.querySelector('.package-list');
const versionList = document.querySelector('.version-list');

packageList.addEventListener('click', function(e) {
    if (e.target.classList.contains('package')) {
        const packageId = e.target.getAttribute('data-id');

        versionList.innerHTML = '';

        fetch(`index.php?package_id=${packageId}`)
            .then(response => response.json())
            .then(versions => {
                versionList.innerHTML = "";

                if (versions.length > 0) {
                    versions.forEach(version => {
                        const li = document.createElement("li");
                        li.textContent = `Version: ${version.version}`;
                        versionList.appendChild(li);
                    });
                } else {
                    versionList.innerHTML = "<li>Aucune version disponible.</li>";
                }
            })
            .catch(err => {
                versionList.innerHTML = "<li>Erreur lors du chargement des versions.</li>";
            });
    }
});
