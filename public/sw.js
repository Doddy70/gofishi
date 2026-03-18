const CACHE_NAME = 'sewa-perahu-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/offline',
  '/assets/front/css/vendors/bootstrap.min.css',
  '/assets/front/css/style.css',
  '/assets/front/js/script.js',
  '/assets/img/6775123ebcb9e.png'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => {
        return caches.match('/offline');
      })
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});

self.addEventListener('push', function (e) {
  if (!(self.Notification && self.Notification.permission === 'granted')) {
    return;
  }

  if (e.data) {
    var msg = e.data.json();
    var options = {
      body: msg.body,
      icon: msg.icon || '/assets/img/6775123ebcb9e.png',
      badge: '/assets/img/6775123ebcb9e.png'
    };
    if (msg.actions && msg.actions.length > 0) {
      options.actions = msg.actions;
    }
    e.waitUntil(self.registration.showNotification(msg.title, options));
  }
});

self.addEventListener('notificationclick', function (e) {
  if (e.action && e.action.length > 0) {
    self.clients.openWindow(e.action);
  } else {
    self.clients.openWindow('/');
  }
  e.notification.close();
});
