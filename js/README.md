# What are these files?

Author: Tianyu Cao

For a detailed documentation, please look at the respective comments in each files.

## For `lineage_network_default.js`

- `1-200`: Define global variables, initialize relation table, and event table.

- `200-2031`: Initialize search and filter menu:

- `316 - 519`: Functions that create search boxes.

- `520 - 555`: Functions that are related to artist relationships.

- `633 - 811`: Functions that relate to auto complete function.

- `813 - 851`: Functions for the "Clear All" button, which clear all search boxes and perform default search.

- `857 - 1156`: Functions that are related to filters, most of them are similar to functions that used to create search boxes.

- `1157 - 1287`: Functions that are related to fetching and checking input from search boxes or filter boxes.

- `1290 - 1620`: Functions for the "Search" button, it will send fetched input to the backend, draw the network graph and display result information.

- `1820 - 1940`: Assign date to each node and edge.

- `2084 - 2170`: Functions that draw network and fetch result information.

- `2336- 2891`: Define LineageNetwork class which contains almost everything related to the network graph including nodes, edges, vis_net object(vis.js, used to draw graph), interface logic and events, such as leftClickEvent, rightClickEvent, and hoverEdgeEvent and so on.

## For `tutorial.js`

- `1 - 32`: First time user window
- `49 - 147`: Welcome window
- `153 - 182`: Select chapter window
- `230 - 330`: Filter tutorial
- `336 - 520`: Search tutorial
- `527 - 777`: Some close, skip functions for tutorial
- `782 - 995`: Functions related to progress bar
- `999 - 1580`: Network Tutorial including Left click, right click, show event tutorial and so on.
- `1580 - 1689`: defines networkElement class which includes some functions that might be useful for some specific network elements(e.g. node, edge).
- `1689 - 1820`: defines Stage class which provides some useful functions for tutorials, for example highLightById(id) function search an element by id and this element on a network graph.
