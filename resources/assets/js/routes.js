import Vue from 'vue';
import Route from 'vue-router';

Vue.use(Route);


export default new Route({
    linkExactActiveClass: 'active',
    routes: [
        {
          path: '/',
          name: 'home',
          component: require('./views/Home').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        },
        {
          path: '/about',
          name: 'about',
          component: require('./views/About').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        },
        {
          path: '/mia',
          name: 'mia',
          component: require('./views/Mia').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        },
        {
          path: '/security',
          name: 'security',
          component: require('./views/Security').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        },
        {
          path: '/promotor',
          name: 'promotor',
          component: require('./views/Promotor').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        },
        {
          path: '/business',
          name: 'business',
          component: require('./views/Business').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        },
        {
          path: '/contact',
          name: 'contact',
          component: require('./views/Contact').default,
          meta: {
            progress: {
              func: [
                {call: 'color', modifier: 'temp', argument: '#ffb000'},
                {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                {call: 'location', modifier: 'temp', argument: 'top'},
                {call: 'transition', modifier: 'temp', argument: {speed: '3.5s', opacity: '0.6s', termination: 400}}
              ]
            }
          }
        }
    ],
    scrollBehavior (to, from, savedPosition) {
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          var navbar = document.querySelector('.navbar');
          navbar.classList.remove('navbar--hide')
          resolve({x: 0, y: 0})
        }, 100)
      })
    }
    //mode: 'history'
});