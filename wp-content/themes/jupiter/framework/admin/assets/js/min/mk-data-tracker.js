(function(window) {
    //I recommend this
    'use strict';

    /**
     * mk-data-tracker.js
     * Author : Reza Marandi
     * 
     * This class is used to track the user interaction in Jupiter Contro Panel.
     * Tracking data includes user's clicks and search queries details for different sections but 
     * statisics will be anonymously be sent to the athor of theme
     * It's a standalone class and we use to manage every action from here related to data tracking.
     * NOTE: this functionality is opt-in. Disabling the tracking in the settings or saying no when asked will cause 
     * this file to not even be loaded.
     **/


    function defineDataTracking() {
        var MkTracker = {};
        var MKTrackerTimeout = 0;
        MkTracker.clickCounter = function(elements) {

            // Validate if elements is object
            if ((typeof elements !== "object") || MkTracker.isEmpty(elements)) {
                console.log('Argument passed is not object');
                return false;
            }

            // Create array of elemenet's ids
            for (var elementsId in elements) {
                var elementsOject = document.querySelectorAll(elementsId);
                for (var counter = 0; counter < elementsOject.length; counter++) {
                    elementsOject[counter].setAttribute('data-mktrackername', elements[elementsId].storageTitle);
                    elementsOject[counter].setAttribute('data-mkcollecttype', elements[elementsId].collectType);
                    elementsOject[counter].setAttribute('data-mkcollect', elements[elementsId].collect);
                }
            }

            MkTracker.addListener("click", document, MkTracker.clickCounter.TriggerAction);
            return true;
        }

        MkTracker.clickCounter.TriggerAction = function(event) {

            var targetElement = event.target || event.srcElement;
            var totalClicksArea = document.querySelectorAll('[data-mkcollect="total-clicks"]');

            for (var i = 0; i < totalClicksArea.length; i++) {
                if (MkTracker.childOf(targetElement, totalClicksArea[i])) {
                    MkTracker.storage('total-clicks', totalClicksArea[i].dataset.mktrackername, 1, totalClicksArea[i].dataset.mkcollecttype);
                }
            }

            if (
                targetElement.hasAttribute("data-mktrackername") &&
                targetElement.hasAttribute("data-mkcollecttype") &&
                targetElement.hasAttribute("data-mkcollect") &&
                targetElement.dataset.mkcollect == 'click'
            ) {
                MkTracker.storage('click', targetElement.dataset.mktrackername, 1, targetElement.dataset.mkcollecttype);
            }

            event.stopPropagation();
            return true;
        }

        MkTracker.loadTimeCalc = function(elements) {
            if ((typeof elements !== "object") || MkTracker.isEmpty(elements)) {
                console.log('Argument passed is not object');
                return false;
            }

            // Create array of elemenet's ids
            for (var elementsId in elements) {
                var elementsOject = document.querySelectorAll(elementsId);
                for (var counter = 0; counter < elementsOject.length; counter++) {
                    elementsOject[counter].setAttribute('data-mktrackername', elements[elementsId].storageTitle);
                    elementsOject[counter].setAttribute('data-mkcollecttype', elements[elementsId].collectType);
                    elementsOject[counter].setAttribute('data-mkcollect', elements[elementsId].collect);
                }
            }

            MkTracker.addListener("load", window, MkTracker.loadTimeCalc.TriggerAction);
            return true;
        }

        MkTracker.loadTimeCalc.TriggerAction = function(event) {

            var elementsOject = document.querySelectorAll('[data-mkcollect="loadtime"]');

            for (var counter = 0; counter < elementsOject.length; counter++) {
                var loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart;
                MkTracker.storage('loadtime', elementsOject[counter].dataset.mktrackername, loadTime, elementsOject[counter].dataset.mkcollecttype);
            }

            return true;
        }


        MkTracker.textCounter = function(elements) {
            
            if ((typeof elements !== "object") || MkTracker.isEmpty(elements)) {
                console.log('Argument passed is not object');
                return false;
            }

            // Create array of elemenet's ids
            for (var elementsId in elements) {
                var elementsOject = document.querySelectorAll(elementsId);
                
                for (var counter = 0; counter < elementsOject.length; counter++) {
                    elementsOject[counter].setAttribute('data-mktrackername', elements[elementsId].storageTitle);
                    elementsOject[counter].setAttribute('data-mkcollecttype', elements[elementsId].collectType);
                    elementsOject[counter].setAttribute('data-mkcollect', elements[elementsId].collect);
                    
                    if (elementsOject[counter].tagName == 'SELECT') {
                        MkTracker.addListener("change", document, MkTracker.textCounter.TriggerAction);
                    } else {
                        MkTracker.addListener("keyup", document, MkTracker.textCounter.TriggerAction);
                    }

                }
            }

            return true;
        }

        MkTracker.textCounter.TriggerAction = function(event) {

            var targetElement = event.target || event.srcElement;
            if (
                targetElement.hasAttribute("data-mktrackername") &&
                targetElement.hasAttribute("data-mkcollecttype") &&
                targetElement.hasAttribute("data-mkcollect") &&
                targetElement.dataset.mkcollect == 'text-values'
            ) {
                // getCounter
                if (targetElement.tagName == 'SELECT') {
                    MkTracker.storage('text-values', targetElement.dataset.mktrackername, [  targetElement.options[targetElement.selectedIndex].text, 1], targetElement.dataset.mkcollecttype);
                } else {
                    if (MKTrackerTimeout){ clearTimeout(MKTrackerTimeout);}                    
                    var targetElementValue = MkTracker.trim(targetElement.value);
                    
                    if (targetElementValue != '') {
                        MKTrackerTimeout = setTimeout(function () {
                            MkTracker.storage('text-values', targetElement.dataset.mktrackername, [  targetElementValue, 1], targetElement.dataset.mkcollecttype);
                        }, 500);
                    }
                }

            }

            event.stopPropagation();
            return true;
        }

        // Store Data
        MkTracker.storage = function(dataType, sectionName, value, collectType) {

            // Need to add value to countable field
            var localStorageData = MkTracker.storage.loadData();
            if (localStorageData == null || localStorageData == undefined) {
                localStorageData = {};
                localStorageData[dataType] = {};
                localStorageData[dataType][sectionName] = {};
            }
            if (MkTracker.isEmpty(localStorageData[dataType])) {
                localStorageData[dataType] = {};
            }
            if (MkTracker.isEmpty(localStorageData[dataType][sectionName]) && collectType !== 'mixed') {
                localStorageData[dataType][sectionName] = {
                    'value': value,
                    'collectType': collectType
                }
            } else if (collectType === 'mixed') {
                
                if (MkTracker.isEmpty(localStorageData[dataType][sectionName])) {
                    localStorageData[dataType][sectionName] = {
                        'value': [],
                        'collectType': collectType
                    }
                }

                var storedValue = localStorageData[dataType][sectionName]['value'];
                
                if(storedValue.filter(function(e) { return e.text == value[0]; }).length > 0) {
                    storedValue.filter(function(e) {
                        if(e.text == value[0]) {
                            e.clicks += parseFloat(value[1])
                        }; 
                    });
                } else {
                    var temp = {};
                    temp['text'] = value[0];
                    temp['clicks'] = value[1];
                    storedValue.push(temp);
                }
               

            } else if (collectType === 'append') {

                var storedValue = localStorageData[dataType][sectionName]['value'];

                if (storedValue.constructor === Array) {
                    storedValue.push(value);
                } else {
                    var temp = [];
                    temp.push(storedValue, value);
                    storedValue = temp;
                }

                localStorageData[dataType][sectionName]['value'] = storedValue;

            } else if (collectType === 'add') {

                if (localStorageData[dataType][sectionName]['value'] == null || localStorageData[dataType][sectionName]['value'] == undefined || localStorageData[dataType][sectionName]['value'] == '') {
                    localStorageData[dataType][sectionName]['value'] = 1;
                } else {
                    localStorageData[dataType][sectionName]['value'] += parseFloat(value);
                }

            } else if (collectType === 'replace') {
                localStorageData[dataType][sectionName]['value'] = value;
            } else {
                console.log('Argument passed is not correct');
                return false;
            }

            localStorageData[dataType][sectionName]['collectType'] = collectType;
            MkTracker.storage.saveData(localStorageData);
            MkTracker.storage.isUpdated(true);
            return true;
        }

        MkTracker.storage.saveData = function(data) {

            // Value update status to true if its not exist in first time
            if (localStorage.getItem("MkTrackingDataUpdateStatus") === null) {
                localStorage.setItem('MkTrackingDataUpdateStatus', false);
            }

            localStorage.setItem('MkTrackingData', JSON.stringify(data));
            return true;
        };

        MkTracker.storage.loadData = function() {
            return JSON.parse(localStorage.getItem('MkTrackingData'));
        };

        MkTracker.storage.retrieveData = function(searchString) {

            var localData = MkTracker.storage.loadData();
            if (localData[searchString] != null) {
                return localData[searchString];
            } else {
                console.log('Element is not defined');
                return false;
            }
        }

        MkTracker.storage.isUpdated = function(status) {
            localStorage.setItem('MkTrackingDataUpdateStatus', status);
        }

        MkTracker.storage.clearData = function(keyName) {
            localStorage.removeItem(keyName);
            return true;
        }

        MkTracker.sendDataToServer = function(serverUrl) {
            if (localStorage.getItem("MkTrackingDataUpdateStatus") == false) {
                return false;
            }
            MkTracker.postAjax(ajaxurl, {
                action: 'mk_frontend_side_tracking_data',
                data: JSON.stringify(MkTracker.storage.loadData())
            }, function(data) {
                data = JSON.parse( data );
                if ( data.hasOwnProperty( 'status' ) ) {
                    if ( data.status ) {
                        MkTracker.storage.isUpdated(false);
                        MkTracker.storage.clearData('MkTrackingData');
                    } else {
                        console.log('Can not save tracking data to server because of : ' + data);
                    }
                } else {
                    console.log('Can not send tracking data to server because of : ' + data);
                }
            });
        }

        MkTracker.postAjax = function(url, data, success) {
            var params = typeof data == 'string' ? data : Object.keys(data).map(
                function(k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
                }
            ).join('&');

            var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
            xhr.open('POST', url);
            xhr.onreadystatechange = function() {
                if (xhr.readyState > 3 && xhr.status == 200) { success(xhr.responseText); }
            };
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(params);
            return xhr;
        }

        MkTracker.addListener = function(evnt, elem, func) {
            if (elem.addEventListener) {
                elem.addEventListener(evnt, func, false);
            } else if (elem.attachEvent) {
                var r = elem.attachEvent("on" + evnt, func);
                return r;
            } else {
                console.log('Can not attache ' + evnt + ' to document');
            }
        }

        MkTracker.isEmpty = function(obj) {

            // null and undefined are "empty"
            if (obj == null) return true;

            // Assume if it has a length property with a non-zero value
            // that that property is correct.
            if (obj.length > 0) return false;
            if (obj.length === 0) return true;

            // If it isn't an object at this point
            // it is empty, but it can't be anything *but* empty
            // Is it empty?  Depends on your application.
            if (typeof obj !== "object") return true;

            // Otherwise, does it have any properties of its own?
            // Note that this doesn't handle
            // toString and valueOf enumeration bugs in IE < 9
            for (var key in obj) {
                if (hasOwnProperty.call(obj, key)) return false;
            }

            return true;
        }

        MkTracker.trim = function(str) {
            // remove while spaces
            return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        }
        
        MkTracker.childOf = function(c, p) {
            while ((c = c.parentNode) && c !== p);
            return !!c;
        }

        MkTracker.init = function(options) {
            // Construct first init
            if (MkTracker.isEmpty(options.tracks)) {
                return false;
            }
            var sendToServer = true;
            var tracks = {};
            var serverUrl = ajaxurl;

            if (options) {
                sendToServer = options.sendToServer || sendToServer;
                tracks = options.tracks || tracks;
                serverUrl = options.serverUrl || serverUrl;
            }

            var clicks = {},
                loadTime = {},
                textValues = {};
            for (var id in tracks) {
                switch (tracks[id].collect) {
                    case 'click':
                    case 'total-clicks':
                        clicks[id] = tracks[id];
                        break;
                    case 'loadtime':
                        loadTime[id] = tracks[id];
                        break;
                    case 'text-values':
                        textValues[id] = tracks[id];
                        break;
                }
            }

            if (!MkTracker.isEmpty(clicks)) {
                MkTracker.clickCounter(clicks);
            }

            if (!MkTracker.isEmpty(loadTime)) {
                MkTracker.loadTimeCalc(loadTime);
            }

            if (!MkTracker.isEmpty(textValues)) {
                MkTracker.textCounter(textValues);
            }

            if (sendToServer) {
                MkTracker.addListener('load', window, MkTracker.sendDataToServer(serverUrl));
            }
        }

        return MkTracker;
    }
    //define globally if it doesn't already exist
    if (typeof(MkTracker) === 'undefined') {
        window.MkTracker = defineDataTracking();
    } else {
        console.log("MkTracker already defined.");
    }
})(window);


/* ========================INIT========================= */


(function() {
    elements = {
        'body[class*="jupiter_page_theme-"]': { collect: 'total-clicks', collectType: 'add', storageTitle: 'control-panel-total-clicks' },
        'a[href*="page=theme-announcements"]': { collect: 'click', collectType: 'add', storageTitle: 'announcements-tab' },
        'a[href*="page=Jupiter"]': { collect: 'click', collectType: 'add', storageTitle: 'register-tab' },
        'a[href*="page=theme-support"]': { collect: 'click', collectType: 'add', storageTitle: 'support-tab' },
        'a[href*="page=theme-plugins"]': { collect: 'click', collectType: 'add', storageTitle: 'plugins-tab' },
        'a[href*="page=theme-addons"]': { collect: 'click', collectType: 'add', storageTitle: 'addons-tab' },
        'a[href*="page=theme-templates"]': { collect: 'click', collectType: 'add', storageTitle: 'templates-tab' },
        'a[href*="page=theme-status"]': { collect: 'click', collectType: 'add', storageTitle: 'status-tab' },
        'a[href*="page=theme-updates"]': { collect: 'click', collectType: 'add', storageTitle: 'updates-tab' },
        'img[src*="register-product-tuts-video.jpg"]': { collect: 'click', collectType: 'add', storageTitle: 'video-tutorial-thumbnail' },
        '.how-to-video-list a i': { collect: 'click', collectType: 'add', storageTitle: 'video-tutorial-thumbnail' },
        'a[href*="docs/how-to-register-theme"] strong': { collect: 'click', collectType: 'add', storageTitle: 'view-tutorial-here-link' },
        'a[href*="why-i-need-to-register-my-theme"]': { collect: 'click', collectType: 'add', storageTitle: 'why-i-need-to-register-my-theme-link' },
        'a[href*="how-can-i-verify-my-api-key"]': { collect: 'click', collectType: 'add', storageTitle: 'how-can-i-verify-my-api-key-link' },
        'a[href*="why-my-api-key-inactive"]': { collect: 'click', collectType: 'add', storageTitle: 'why-my-api-key-inactive-link' },
        'a[href*="what-are-the-benefits-of-registration"]': { collect: 'click', collectType: 'add', storageTitle: 'what-are-the-benefits-of-registration-link' },
        'a[href*="how-can-i-obtain-my-purchase-code"]': { collect: 'click', collectType: 'add', storageTitle: 'how-can-i-obtain-my-purchase-code-link' },
        'a[href*="i-get-this-error-when-registering-my-theme-duplicated-purchase-key-detected"]': { collect: 'click', collectType: 'add', storageTitle: 'i-get-this-error-when-registering-my-theme-duplicated-purchase-key-detected-link' },
        'a[href$="artbees.net/support/jupiter/docs"]': { collect: 'click', collectType: 'add', storageTitle: 'documentation-link' },
        'a[href$="artbees.net/support/jupiter/videos"]': { collect: 'click', collectType: 'add', storageTitle: 'videos-link' },
        'a[href$="artbees.net/support/jupiter/faq"]': { collect: 'click', collectType: 'add', storageTitle: 'faq-link' },
        'a[href$="artbees.net/support/jupiter"]': { collect: 'click', collectType: 'add', storageTitle: 'ask-our-experts-link' },
        'a[href$="artbees.net/c/jupiter"]': { collect: 'click', collectType: 'add', storageTitle: 'join-community-link' },
        'a[href$="artbees.net/artbees-care"]': { collect: 'click', collectType: 'add', storageTitle: 'customise-jupiter-link' },
        'a[href*="/?post_type=announcement"]': { collect: 'click', collectType: 'add', storageTitle: 'announcement-post-link' },
        '.mk-template-item-btn-preview': { collect: 'click', collectType: 'add', storageTitle: 'template-preview-btn' },
        '.mk--update-btn': { collect: 'click', collectType: 'add', storageTitle: 'update-btn' },

        'ul[class*="cp-tabs-holder"]': { collect: 'loadtime', collectType: 'append', storageTitle: 'control-panel-loadtime' },


        '.mk-templates-categories': { collect: 'text-values', collectType: 'mixed', storageTitle: 'template-categories-data' },
        '.mk-search-template': { collect: 'text-values', collectType: 'mixed', storageTitle: 'template-search-data' },

    };

    MkTracker.init({
        tracks: elements,
        sendToServer: true,
        serverUrl: ajaxurl
    });
})();
