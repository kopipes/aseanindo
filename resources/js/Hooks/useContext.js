import { usePage } from "./usePage.js"


export const useContext = () => {
     const page = usePage()
     const username = page.company.username

     const showAlert = (message) => {
          const buttonShowAlert = document.getElementById('show-alert')
          if (buttonShowAlert) {
               document.getElementById('alert-content').innerHTML = message
               buttonShowAlert.click()
          }
     }
     const loading = {
          toggle: () => {
               const buttonShowLoading = document.getElementById('toggle-loading')
               if (buttonShowLoading) {
                    buttonShowLoading.click()
               }
          },
          hide: () => {
               const buttonHideLoading = document.getElementById('hide-toggle-loading')
               if (buttonHideLoading) {
                    buttonHideLoading.click()
               }
          }
     }

     const cookie = {
          set: (name, value) => {
               var currentCache = window.localStorage.getItem(username) || null
               if (currentCache) {
                    currentCache = JSON.parse(currentCache)
               } else {
                    currentCache = {}
               }
               window.localStorage.setItem(username, JSON.stringify({
                    ...currentCache,
                    ...{
                         [name]: value
                    }
               }))
          },
          get: (name) => {
               var currentCache = window.localStorage.getItem(username) || null
               if (currentCache) {
                    currentCache = JSON.parse(currentCache)
                    return currentCache[name]
               }
               return null
          },
          del: (name) => {
               var currentCache = window.localStorage.getItem(username) || null
               if (currentCache) {
                    currentCache = JSON.parse(currentCache)
                    delete currentCache[name]
                    window.localStorage.setItem(username, JSON.stringify(currentCache))
               }
               return null
          },
          setWithExpired(key, value, days = 1) {
               let result = {
                    [key]: {
                         data: value,
                         expireTime: Date.now() + (days * 30 * 60 * 1000)
                    },
               }
               window.localStorage.setItem(username, JSON.stringify(result));
          },
          getWithValidate(key) {
               var currentCache = window.localStorage.getItem(username) || null
               if (currentCache) {
                    currentCache = JSON.parse(currentCache)
                    const result = currentCache[key]
                    if (result) {
                         if (result.expireTime <= Date.now()) {
                              window.localStorage.removeItem(key);
                              return null;
                         }
                         return result.data;
                    }
               }
               return null;
          }
     }

     const pageReloadListener = {
          confirmReload: (event) => {
               event.preventDefault();
               event.returnValue = "";
               return;
          },
          listen: () => {
               if (cookie.get("call_id")) {
                    window.addEventListener("beforeunload", pageReloadListener.confirmReload);
               }
          },
          destroy: () => {
               window.removeEventListener("beforeunload", pageReloadListener.confirmReload);
          }
     }

     const route = {
          appendURLParam: (params, silent = true) => {
               const newUrl = new URL(window.location.href);
               for (var key in params) {
                    newUrl.searchParams.set(key, params[key]);
               }
               window.history.pushState({}, '', newUrl);
               if (!silent) {
                    window.dispatchEvent(new Event('changeUrlParameter'));
               }
          },
          removeURLParameter: (params, silent = true) => {
               const url = new URL(window.location.href);
               for (var key in params) {
                    url.searchParams.delete(params[key]);
               }
               window.history.replaceState({}, document.title, url.toString());
               if (!silent) {
                    window.dispatchEvent(new Event('changeUrlParameter'));
               }
          }
     }


     const base64encode = (string) => window.btoa(string)
     const base64decode = (string) => window.atob(string)
     return {
          showAlert,
          loading,
          cookie,
          page,
          base64encode,
          base64decode,
          pageReloadListener,
          route
     }
}
