# PageSummariesAPI
Uses designated text or the first paragraph of MediaWiki article as a "summary" and adds it to the API and parser.

This extension was a solution to the problem that MediaWiki doesn't offer any kind of article abstract. However, based on the way articles are written on [The Unified Republic of Stars](https://unifiedrepublicofstars) and [Stats For Sharks](https://statsforsharks) it seemed pretty clear that first paragraph could be used for this purpose as they were generally written to summarize what was to follow.

Thus, this plugin was born.

It's extremely easy to use. When the `parse` API is used, it just adds the first paragraph or any text marked between `<summary>` tags to the returned data.

Additionally, it adds a `summaries` API to the MediaWiki API set.

`api.php?action=summaries&pages=Main_Page|Example` will return the "summaries" for every page included (separated by a "|") as a result set that can feed... whatever. It's fast too, as it takes advantage of a few internal MediaWiki APIs to cut out some bullshit. It even utilizes the `$wgArticlePath` configuration variable to include a fully resolved URL as well.

Text designated with a `<summary>` tag can take one option `show="[default:true|false]"`. Leaving it out or marking it as `true` will have the summary text display as any other text in a wiki entry might. Marking it as `false` means that it _will not_ appear in the standard wiki article display but it will appear in the APIs above as the summary.

Not much else to say about it. It's pretty much just a tool to help build other things.