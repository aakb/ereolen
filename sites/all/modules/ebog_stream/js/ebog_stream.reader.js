function Reader(settings) {

    if (typeof settings.elementId == 'undefined')
        throw 'Argument elementId is required.';

    if (typeof settings.orderId == 'undefined')
        throw 'Argument orderId is required.';

    if (typeof settings.sessionKeyUrl == 'undefined')
        throw 'Argument sessionKeyUrl is required.';

    var bookmarker;
    var bookRenderer;
    var zoomer;
    var toc;
    var spinner;
    var modalWindow;

    // Styles MUST be inline - otherwise epub css might override the styles
    var defaultMarkup = '<div id="' + ElementId.ReaderHeader + '">' +
                            '<a href="javascript:reader.ZoomOut();" id="reader-link-zoomout"><img src="http://reader.pubhub.dk/images/zoom_out.png" title="Zoom ud" border="0" style="width:42px;height:42px;" /></a>' +
                            '<a href="javascript:reader.ZoomNormal();" id="reader-link-zoomnormal"><img src="http://reader.pubhub.dk/images/zoom_normal.png" title="Zoom normal" border="0" style="width:42px;height:42px;" /></a>' +
                            '<a href="javascript:reader.ZoomIn();" id="reader-link-zoomin"><img src="http://reader.pubhub.dk/images/zoom_in.png" title="Zoom in" border="0" style="width:42px;height:42px;" /></a>' +
                            '<a href="javascript:reader.ToggleBookmark();"><img src="http://reader.pubhub.dk/images/bookmark_new.png" title="Tilføj/fjern bogmærke" border="0" style="width:42px;height:42px;" /></a>' +
                            '<a href="javascript:reader.DisplayAddNoteWindow();"><img src="http://reader.pubhub.dk/images/note_add.png" title="Tilføj/ret note" border="0" style="width:42px;height:42px;margin-left:10px;" /></a>' +
                            '<a href="javascript:reader.DisplayBookmarkList();"><img src="http://reader.pubhub.dk/images/mark_list.png" title="Vis bogmærker & noter" border="0" style="margin-left:10px;width:42px;height:42px;" /></a>' +
                            '<a href="javascript:reader.DisplayToc();"><img src="http://reader.pubhub.dk/images/toc.png" title="Indholdsfortegnelse" border="0" style="margin-left:10px;width:42px;height:42px;" /></a>' +
                        '</div>' +
                        '<div id="reader-navigation-top">' +
                            '<a href="javascript:reader.PrevPage();"><img src="http://reader.pubhub.dk/images/arrow_left.png" alt="Forrige side" border="0" style="left:0px;position:absolute;margin:10px;width:17px;height:30px;"/></a>' +
                            '<div id="reader-bookmark-mobile" onclick="javascript:reader.RemoveBookmark();"></div>' +
                            '<div id="reader-note-mobile" onclick="javascript:reader.RemoveNote();"></div>' +
                            '<a href="javascript:reader.NextPage();"><img src="http://reader.pubhub.dk/images/arrow_right.png" alt="Næste side" border="0" style="right:0px;position:absolute;margin:10px;width:17px;height:30px;" /></a>' +
                        '</div>' +
                        '<div id="reader-content-container">' +
                            '<div id="reader-content">' +
                                '<div id="reader-navigation-left" style="text-align:left;">' +
                                    '<a href="#" id="reader-button-previouspage" onclick="javascript:reader.PrevPage()">' +
                                        '<img src="http://reader.pubhub.dk/images/arrow_left.png" alt="Forrige side" id="reader-button-previouspage-image" border="0" style="width:17px;height:30px;" />' +
                                    '</a>' +
                                '</div>' +
                                '<div id="reader-book"><div id="reader-book-inner-div" i="0"></div></div>' +
                                '<div id="reader-navigation-right" style="text-align:left;">' +
                                    '<img src="http://reader.pubhub.dk/images/bookmark.png" id="reader-bookmark" alt="Fjern bogmærke" onclick="javascript:reader.RemoveBookmark();"  style="width:55px;height:75px;" />' +
                                    '<img src="http://reader.pubhub.dk/images/note.png" id="reader-note" alt="Fjern note" onclick="javascript:reader.RemoveNote();"  style="width:55px;height:75px;" />' +
                                    '<a href="#" id="reader-button-nextpage" onclick="javascript:reader.NextPage();">' +
                                        '<img src="http://reader.pubhub.dk/images/arrow_right.png" alt="Næste side" id="reader-button-nextpage-image" border="0" style="width:17px;height:30px;" />' +
                                    '</a>' +
                                '</div>' +
                            '</div>' +
                            '<div id="reader-modal-window">' +
                                '<div id="reader-modal-window-header">' +
                                    '<span id="reader-modal-window-header-title"></span>' +
                                    '<a id="reader-modal-window-button-close" href="javascript:reader.CloseModalWindow();"><img src="http://reader.pubhub.dk/images/close.png" border="0" alt="Luk" style="width:13px;height:12px;" /></a>' +
                                '</div>' +
                                '<div id="reader-modal-window-content"></div>' +
                            '</div>' +
                        '</div>' +
                        '<span id="reader-rendertime" style="display:none;"></span>';

    var numberOfSuccessCallbacksCalled = 0;
    this.Display = function () {
        LoadDependencies();
    }

    var numberOfDependenciesLoaded = 0;
    var numberOfDependeciesToBeLoaded;
    function LoadDependencies() {
        numberOfDependeciesToBeLoaded = 13;
        //LoadScript('scripts/jquery-1.7.2.min.js', function () {
            LoadDependency('scripts/jquery.browser.mobile.js');
            LoadDependency('scripts/spin.min.js');
            LoadDependency('scripts/jquery.cj-swipe.js');
            LoadDependency('scripts/reader-1.0.9/browser.js');
            LoadDependency('scripts/reader-1.0.9/serviceurl.js');
            LoadDependency('scripts/reader-1.0.9/toc.js');
            LoadDependency('scripts/reader-1.0.9/modalwindow.js');
            LoadDependency('scripts/reader-1.0.9/security.js');
            LoadDependency('scripts/reader-1.0.9/zoomer.js');
            LoadDependency('scripts/reader-1.0.9/epubloader.js');
            LoadDependency('scripts/reader-1.0.9/bookrenderer.js');
            LoadDependency('scripts/reader-1.0.9/bookmarker.js');
            LoadDependency('scripts/reader-1.0.9/readerspinner.js');
            //LoadDependency('scripts/reader-1.0.9/reader-1.0.9.css');
        //});
    }

    function LoadDependency(path) {
        var extension = path.substring(path.lastIndexOf('.'));

        if (extension == '.js') {
            LoadScript(GetReaderBaseUri() + path, function () {
                numberOfDependenciesLoaded++; if (numberOfDependenciesLoaded == numberOfDependeciesToBeLoaded) SetSessionKeyAndInitReader();
            });
        }

        if (extension == '.css') {
            LoadStylesheet(function () {
                numberOfDependenciesLoaded++; if (numberOfDependenciesLoaded == numberOfDependeciesToBeLoaded) SetSessionKeyAndInitReader();
            }, GetReaderBaseUri() + path);
        }
    }

    function LoadStylesheet(onload, href) {
        var id = href.substring(href.lastIndexOf('/') + 1, href.lastIndexOf('.'));
        $.get(href, function (response) {
            if (!$('#' + id).length) $('head').append('<style id="' + id + '">' + response + '</style>');
            onload();
        });
    }

    function LoadScript(url, success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0];
        var done = false;

        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function () {
            if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };

        head.appendChild(script);
    }

    function SetSessionKeyAndInitReader() {
        spinner = new ReaderSpinner(settings.elementId);
        spinner.Start();

        ServiceUrl.SetSessionKeyUrl(settings.sessionKeyUrl);
        ServiceUrl.SetSessionKey(settings.sessionKeyUrl, function () {
            Init();
        });
    }

    function Init() {
        new Security().PreventCopy();

        $('#' + settings.elementId).append(defaultMarkup);

        var $readerBook = $('#reader-book');
        var $readerHeader = $('#reader-header');

        if ($.browser.mobile) {
            // Hide left navigation
            $('#reader-navigation-left').css('display', 'none');
            $('#reader-book').css('left', '0');

            // Hide right navigation
            $('#reader-navigation-right').css('display', 'none');
            $('#reader-book').css('right', '0');

            $('#reader-link-zoomout').css('display', 'none');
            $('#reader-link-zoomnormal').css('display', 'none');
            $('#reader-link-zoomin').css('display', 'none');

            $('#reader-modal-window').css('height', $readerBook.height() - 10 + 'px');
            $('#reader-modal-window-content').css('height', ($readerBook.height() - 46) + 'px');

            ActivateMenuClick();

            $readerHeader.delay(5000).slideUp();
        }
        else {
            $('#reader-modal-window').css('top', '10%');
        }

        var streamingServiceBaseUri = GetStreamingServiceBaseUri();

        bookmarker = new Bookmarker(settings.orderId);
        toc = new Toc(streamingServiceBaseUri, settings.orderId);
        bookRenderer = new BookRenderer(settings.orderId, bookmarker, spinner);
        zoomer = new Zoomer($readerBook, bookRenderer);
        modalWindow = new ModalWindow();

        bookRenderer.InitNavigationKeys();

        bookmarker.LoadBookmarks(function () {
            bookRenderer.DisplayByReadingState();
        });

        counter = 0;
    }

    SetSessionKey = function (sessionKeyUrl, callback) {
        $.ajax({
            url: sessionKeyUrl,
            dataType: 'json',
            success: function (data) {
                ServiceUrl.SessionKey = data.SessionId;
                callback();
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(xhr.status);
            }
        });
    }

    this.DisplayChapter = function (index) {
        this.CloseModalWindow();
        bookRenderer.DisplayHtmlPage(index);
    }

    this.NextPage = function () {
        bookRenderer.Next();
    }

    this.PrevPage = function () {
        bookRenderer.Back();
    }

    this.ZoomIn = function () {
        zoomer.ZoomIn();
    }

    this.ZoomOut = function () {
        zoomer.ZoomOut();
    }

    this.ZoomNormal = function () {
        zoomer.ZoomNormal();
    }

    this.AddBookmark = function () {
        bookmarker.AddBookmark(bookRenderer.GetCurrentHtmlPageIndex(), bookRenderer.IndexOfFirstElementOnPage(), bookRenderer.GetFirstXCharactersOfCurrentPage(bookmarker.numberOfCharactersInBookmarkDescription));
    }

    this.ToggleBookmark = function () {
        bookmarker.ToggleBookmark(bookRenderer.GetCurrentHtmlPageIndex(), bookRenderer.IndexOfFirstElementOnPage(), bookRenderer.GetFirstXCharactersOfCurrentPage(bookmarker.numberOfCharactersInBookmarkDescription));
        HideMenu();
    }

    this.RemoveBookmark = function () {
        bookmarker.RemoveBookmark();
    }

    this.RemoveNote = function () {
        bookmarker.RemoveNote();
    }

    this.DisplayAddNoteWindow = function () {
        HideMenuAndDeactivateMenuClick();
        bookmarker.DisplayAddNoteWindow(bookRenderer.GetCurrentHtmlPageIndex(), bookRenderer.IndexOfFirstElementOnPage(), bookRenderer.IndexOfLastElementOnPage(), modalWindow);
    }

    this.AddNote = function (text) {
        bookmarker.AddNote(bookRenderer.GetCurrentHtmlPageIndex(), bookRenderer.IndexOfFirstElementOnPage(), $('#note-text').val());
        this.CloseModalWindow();
    }

    this.UpdateNote = function (markId, text) {
        bookmarker.UpdateNote(markId, $('#note-text').val());

        // Prevent that the top menu slides down on mobile devices
        if (window.event && window.event.stopPropagation) // stopPropagation doesn't work in IE
            window.event.stopPropagation();

        this.CloseModalWindow();
    }

    this.DisplayBookmarkList = function () {
        toc.GetToc({
            success: function (data) {
                bookmarker.DisplayBookmarkList(data, modalWindow);
            }
        });

        HideMenuAndDeactivateMenuClick();
    }

    this.GoToBookmark = function (bookmarkId) {
        var bookmark = bookmarker.GetBookmarkById(bookmarkId);
        if (bookmark != null)
            bookRenderer.DisplayByHtmlPageIndexAndElementIndex(bookmark.chapterIndex, bookmark.elementIndex);
        else
            alert('Bogmærket blev ikke fundet.');
        this.CloseModalWindow();
    }

    this.CloseModalWindow = function () {
        ActivateMenuClick();
        modalWindow.Close();
    }

    this.DisplayToc = function () {
        toc.DisplayToc(modalWindow);
        HideMenuAndDeactivateMenuClick();
    }

    function HideMenuAndDeactivateMenuClick() {
        HideMenu();
        DeactivateMenuClick();
    }

    function HideMenu() {
        if ($.browser.mobile)
            $('#reader-header').slideUp();
    }

    function ShowMenu() {
        if ($.browser.mobile)
            $('#reader-header').slideDown();
    }

    function DeactivateMenuClick() {
        if ($.browser.mobile)
            $('#reader-content-container').unbind('click');
    }

    function ActivateMenuClick() {
        if ($.browser.mobile) {
            $('#reader-content-container').bind('click', function () {
                var $readerHeader = $('#reader-header');
                if ($readerHeader.css('display') == 'none')
                    $readerHeader.slideDown();
                else
                    $readerHeader.slideUp();
            });
        }
    }

    function GetStreamingServiceBaseUri() {
        if (window.location.hostname == 'localhost')
            return 'http://localhost:15495/publicstreaming/v1/';
        return 'http://streaming.bogskyen.dk/publicstreaming/v1/';
    }

    function GetReaderBaseUri() {
        if (window.location.hostname == 'localhost')
            return 'http://localhost:64286/';
        return 'http://reader.pubhub.dk/';
    }

}


function ElementId() { }
ElementId.ReaderHeader = 'reader-header';
