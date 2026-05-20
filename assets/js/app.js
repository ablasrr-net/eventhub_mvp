async function loadEvents(filters = {}) {

    const container =
    document.getElementById('events-container');

    container.innerHTML = `
        <div class="loading">
            Chargement des événements...
        </div>
    `;

    try {

        const query =
        new URLSearchParams(filters);

        const response = await fetch(
            'api/events.php?' + query
        );

        // Vérification erreur serveur
        if(!response.ok){

            throw new Error('Erreur API');
        }

        const events = await response.json();

        // Vérification tableau
        if(!Array.isArray(events)){

            throw new Error('JSON invalide');
        }

        // Aucun événement
        if(events.length === 0) {

            container.innerHTML = `
                <div class="empty">
                    Aucun événement trouvé
                </div>
            `;

            return;
        }

        container.innerHTML = '';

        events.forEach(event => {

            const isFull =
            event.registered_count >= event.capacity;

            container.innerHTML += `

                <div class="event-card">

                    <div class="event-image">
                        <img
                        src="assets/img/event.jpg"
                        alt="event">
                    </div>

                    <div class="event-content">

                        <div class="event-badge">
                            ${event.category || 'Business'}
                        </div>

                        <h3>
                            ${event.title}
                        </h3>

                        <p class="location">
                            📍 ${event.location}
                        </p>

                        <p class="description">
                            ${event.description || 'Événement professionnel premium'}
                        </p>

                        <div class="event-footer">

                            <div class="capacity">
                                👥
                                ${event.registered_count}/${event.capacity}
                            </div>

                            <button
                            onclick="registerToEvent(${event.id})"
                            ${isFull ? 'disabled' : ''}
                            >

                            ${isFull
                                ? 'Complet'
                                : "S'inscrire"}

                            </button>

                        </div>

                    </div>

                </div>
            `;
        });

    } catch(error) {

        console.error(error);

        container.innerHTML = `
            <div class="error">
                Impossible de charger les événements
            </div>
        `;
    }
}

async function registerToEvent(eventId) {

    try {

        const formData = new FormData();

        formData.append('event_id',eventId);

        formData.append('user_id',2);

        const response = await fetch(
            'events/register.php',
            {
                method:'POST',
                body:formData
            }
        );

        const data = await response.json();

        if(data.success) {

            alert("Inscription réussie");

            loadEvents();

        } else {

            alert(data.message || "Erreur");
        }

    } catch(error){

        alert("Erreur serveur");
    }
}

/*
Recherche dynamique avec debounce
*/

let debounceTimer;

document.getElementById('search')
.addEventListener('input', function() {

    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {

        loadEvents({
            keyword:this.value
        });

    },400);
});

loadEvents();