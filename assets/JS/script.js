const isAdmin = true; // Remplacez par une condition réelle selon votre application

const authBtn = document.querySelector('input[name="add_author"]');
authBtn.addEventListener('click', function () {
    document.querySelector('.auteur-fields').style = "display: block";
});

const packBtn = document.querySelector('input[name="add_package"]');
packBtn.addEventListener('click', function () {
    document.querySelector('.package-fields').style = "display: block";
});

const versionBtn = document.querySelector('input[name="add_version"]');
versionBtn.addEventListener('click', function () {
    document.querySelector('.version-fields').style = "display: block";
});

const packageList = document.querySelector('.package-list');
const versionList = document.querySelector('.version-list');

packageList.addEventListener('click', function (e) {
    if (e.target.classList.contains('package')) {
        const packageId = e.target.getAttribute('data-id');

        versionList.innerHTML = '';

        fetch(`../../index1.php?package_id=${packageId}`)
            .then(response => response.json())
            .then(versions => {
                versionList.innerHTML = "";

                if (versions.length > 0) {
                    versions.forEach(version => {
                        const li = document.createElement("li");
                        li.innerHTML = `Version: ${version.version}`;
                        li.dataset.versionId = version.id;

                        // Si admin, ajoutez le bouton supprimer
                        if (isAdmin) {
                            // const deleteBtn = document.createElement("button");
                            li.innerHTML += `
                                <form method='POST' class="delete-version" style='display:inline;'>
                                    <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                                    <input type='hidden' name='version_id' value='{$version['id']}'>
                                    <button type='submit' name='delete_version' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette version ?\")'>
                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor' width='20px' class='size-5'>
                                            <path fill-rule='evenodd' d='M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z' clip-rule='evenodd' />
                                        </svg>
                                    </button>
                                </form>
                            `;
                        }

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


versionList.addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-version')) {
        const versionId = e.target.parentElement.dataset.versionId;

        fetch(`../../index1.php?id=${versionId}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    e.target.parentElement.remove();
                    alert("Version supprimée avec succès.");
                } else {
                    alert("Erreur lors de la suppression.");
                }
            })
            .catch(error => console.error(error));
    }
});
