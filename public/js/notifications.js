let notificationPopover;


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

export async function updateNotifications() {
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
            notificationPopover._config.content = html, 0;
            notificationPopover.setContent();
            dismissNotificationsButton();
        })
        .catch(function (err) {
            console.log('Failed to fetch page: ', err);
        });


}

export async function updateNotificationCount() {
    return await fetch('/api/notifications/count', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
    })
        .then(function (response) {
            return response.text()
        })
        .then(function (count) {
            console.log(count);
            const notificationButton = document.getElementById('notification-button');
            const notificationCount = document.getElementById('notification-count');

            if (notificationButton && count > 0) {
                if (notificationButton && notificationCount) { notificationCount.innerHTML = count; }
                else if (notificationButton) {
                    const span = document.createElement('span');
                    span.id = 'notification-count';
                    span.classList = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                    span.innerHTML = count;
                    const spanHidden = document.createElement('span');
                    spanHidden.classList = 'visually-hidden';
                    spanHidden.innerHTML = 'unread messages'

                    notificationButton.appendChild(span);
                    span.appendChild(spanHidden);
                }
            } else if (notificationButton && notificationCount && count == 0) {
                clearNotificationCount();
            }
        })
        .catch(function (err) {
            console.log('Failed to fetch page: ', err);
        });
}

export function update() {
    updateNotifications();
    updateNotificationCount();
}

export function notificationPopup(url, data) {
    const main = document.getElementsByTagName("main")[0];
    const extDiv = document.createElement("div");
    extDiv.classList = "alert alert-dismissible alert-dismissable alert-info position-absolute bottom-0 end-0 m-5 d-flex flex-row";
    const button = document.createElement("button");
    button.type = "button";
    button.classList = "btn-close";
    button.dataset.bsDismiss = "alert";
    button.addEventListener('click', (e) => {
        e.preventDefault();
    });
    const strong = document.createElement("strong");
    strong.innerHTML = data.message + ".\u00A0"; //Non-breaking white space
    const a = document.createElement("a");
    a.classList = "alert-link";
    a.href = url;
    a.addEventListener('click', function (e) {
        const targetUrl = document.activeElement.href;
        const id = targetUrl.match("([^\/]+)\/?$")[0];

    });

    main.appendChild(a);
    a.appendChild(extDiv);
    extDiv.appendChild(button);
    extDiv.appendChild(strong);
}

export function markQuestionNotifRead() {
    const questionId = window.location.href.split('/').pop();
    makePostRequest('/notifications/read/question/' + questionId);
    updateNotificationCount();
}

function upvotePopup(data) {
    console.log(`New upvote: ${data.message}`);

    let url = '/questions/';

    switch (data.vote.type) {
        case 'ANSWER': url = url + data.vote.content.question_id; break;
        case 'QUESTION': url = url + data.vote.question_id; break;
    }

    console.log(`${url}`);

    notificationPopup(url, data);
}

function answerPopup(data) {
    console.log(`New answer: ${data.message}`);

    let url = '/questions/' + data.question.id;

    console.log(`${url}`);

    notificationPopup(url, data);
}

export function clearNotificationCount() {
    const notificationButton = document.getElementById('notification-button');
    const notificationCount = document.getElementById('notification-count');
    if (notificationButton && notificationCount) { notificationButton.removeChild(notificationCount); }
}

export function notificationButton() {
    const notificationButton = document.getElementById('notification-button');

    if (notificationButton) {
        notificationButton.addEventListener('click', (e) => {
            makePostRequest('/notifications/read');
            clearNotificationCount();
        });
    }
}

export function dismissNotificationsButton() {
    const deleteAllNotifs = document.getElementById('dismiss-notifications');
    if (deleteAllNotifs) {
        deleteAllNotifs.addEventListener('click', function (data) {
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
            update();
        });

        channel.bind('notification-answer', function (data) {
            answerPopup(data);
            update();
        });
    }

    notificationButton();

    document.addEventListener('DOMContentLoaded', function () {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            notificationPopover = new bootstrap.Popover(popoverTriggerEl, {
                template: '<div class="popover"><div class="popover-arrow"></div><h3 class="popover-header">Notifications</h3><div id="popover-body-notifications" class="popover-body list-group d-flex flex-column list-unstyled p-0"></div></div>',
                container: 'body',
                html: true,
                placement: 'bottom',
            });
            updateNotifications();
            popoverTriggerEl.addEventListener('hidden.bs.popover', updateNotifications);
            return notificationPopover;
        });

        notificationPopover._element.addEventListener('shown.bs.popover', function (event) {
            const deleteAllNotifs = document.getElementById('dismiss-notifications');

            if (deleteAllNotifs) {
                deleteAllNotifs.addEventListener('click', function (data) {
                    makePostRequest("/notifications/delete");
                    update();
                });
            }

            const deleteNotifs = document.getElementsByClassName('dismiss-notification');
            for (const button of deleteNotifs) {
                button.addEventListener('click', function (e) {
                    const notificationId = e.target.parentElement.id.split('-').pop();
                    makePostRequest("/notifications/delete/" + notificationId);
                    update();
                })
            }
        })
    });


}







