const o=/(?:http|https)?:?\/?\/?(?:www\.)?(?:player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|video\/|)([a-z-0-9]+)(?:|\/\?)/;export function getVimeoVideoIdFromUrl(t){const e=t.match(o);return e?e[1]:0}