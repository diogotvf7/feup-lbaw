async function makePostRequest(url) {
    return await fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
        .catch(function (err) {
            console.log('Failed to fetch page: ', err);
        });
}

export async function update() {
    return await fetch('/api/notifications/', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
    })
        .then(function (response) {
            return response.text()
        })
        .then(function (html) {
            const notifDiv = document.getElementById('notifications');
            notifications.innerHTML = html;
        })
        .catch(function (err) {
            console.log('Failed to fetch page: ', err);
        });
}

async function triggerEvent() {
    return await fetch('/votes/event', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(function (response) {
            return response.text()
        })
        .then(function (response) {
            console.log('Triggered event\n');
            console.log(response);
        })
        .catch(function (err) {
            console.log('Failed to fetch page: ', err);
        });
}

function upvotePopup(data) {
    console.log(`New upvote: ${data.message}`);

    let url = '/questions/';

    switch (data.vote.type) {
        case 'ANSWER': url = url + data.vote.content.question_id; break;
        case 'QUESTION': url = url + data.question_id; break;
    }

    console.log(`${url}`);

    const main = document.getElementsByTagName("main")[0];
    const extDiv = document.createElement("div");
    extDiv.classList = "alert alert-dismissible alert-dismissable alert-info position-absolute bottom-0 end-0 m-5 d-flex flex-row";
    const button = document.createElement("button");
    button.type = "button";
    button.classList = "btn-close";
    button.dataset.bsDismiss = "alert";
    const strong = document.createElement("strong");
    strong.innerHTML = data.message + ".\u00A0"; //Non-breaking white space
    const a = document.createElement("a");
    a.classList = "alert-link";
    a.href = url;
    a.innerText = "Check it here"
    main.appendChild(extDiv);
    extDiv.appendChild(button);
    extDiv.appendChild(strong);
    extDiv.appendChild(a);
}

export function clearNotificationCount() {
    document.getElementById('notification-button').removeChild(document.getElementById('notification-count'));
}

export function notificationButton() {
    const notificationButton = document.getElementById('notification-button');
    const notifications = document.getElementById('notifications');

    notificationButton.addEventListener('click', (e) => {
        triggerEvent();
        makePostRequest('/notifications/read');
        clearNotificationCount();
        notifications.classList.toggle('d-none');
    }
    )
}

export function dismissNotificationsButton() {
    const deleteNotif = document.getElementById('dismiss-notifications');
    if (deleteNotif) {
        deleteNotif.addEventListener('click', function (data) {
            makePostRequest("/notifications/delete");
            update();
        });
    }
}

export default function enableNotifications() {
    Pusher.logToConsole = true;

    const pusher = new Pusher("37abc4ec3e719eac9ea4", {
        cluster: 'eu',
        encrypted: true,
        authEndpoint: "/broadcasting/auth",
        forceTLS: true,
    });

    if (userId !== '') {
        const channelName = 'private-user-' + userId;
        const channel = pusher.subscribe(channelName);

        let main = document.getElementsByTagName('main')[0];

        channel.bind('notification-upvote', function (data) {
            upvotePopup(data);
        });
    }

    notificationButton();
    dismissNotificationsButton();
}






