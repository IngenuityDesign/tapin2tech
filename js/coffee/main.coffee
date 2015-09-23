
(($) ->
   $ ->
    $('.activator').on "click", (e) ->
      e.preventDefault
      isClosed = if $(this).hasClass "closed" then true else false
      if isClosed then doOpen $(this) else doClose $(this)

    doOpen = (btn) ->
      btn.removeClass "closed"
        .addClass "opened"
      $('.carousel-inner .item .bottom').addClass "active"

    doClose = (btn) ->
      btn.removeClass "opened"
        .addClass "closed"
      $('.carousel-inner .item .bottom').removeClass "active"

) jQuery



