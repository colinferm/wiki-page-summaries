# PageSummariesAPI
Uses the first paragraph of text in a MediaWiki article as a "summary" and adds it to the API and parser.

This extension was a solution to the problem that MediaWiki doesn't offer any kind of article abstract. However, based on the way articles are written on [The Unified Republic of Stars](https://unifiedrepublicofstars) and [Stats For Sharks](https://statsforsharks) it seemed pretty clear that first paragraph could be used for this purpose as they were generally written to summarize what was to follow.

Thus, this plugin was born.

It's extremely easy to use. When the `parse` API is used, it just adds the first paragraph to the returned data.

Additionally, it adds a `summaries` API to the MediaWiki API set.

`api.php?action=summaries&pages=Main_Page|Example` will return the "summaries" for every page included (separated by a "|") as a result set that can feed... whatever. It's fast too, as it takes advantage of a few internal MediaWiki APIs to cut out some bullshit. It even utilizes the `$wgArticlePath` configuration variable to include a fully resolved URL as well.

Not much else to say about it. It's pretty much just a tool to help build other things.
