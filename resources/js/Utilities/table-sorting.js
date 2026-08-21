/**
 * Sort table rows
 *
 * Version: 2.0.0
 *
 * Usage: Add 'sortable-string', 'sortable-number', or 'sortable-date' class to table headers to make them sortable. Use data-sort-value attribute for formatted values that need numeric/date sorting.
 *
 * Initialize: initSortableTables('sortable-table')
 */


/**
 * Initialize sortable tables
 *
 * Finds all tables with the specified class and sets up click handlers on their headers so users can click any header with a sortable class to sort that column.
 * Add data-show-sort-arrows="false" to the table element to disable sort direction arrows.
 *
 * @param {string} tableClass - The CSS class name of tables to make sortable (e.g., 'sortable-table').
 *
 * @returns {void}
 */
export function initSortableTables(tableClass) {
    const tables = document.querySelectorAll(`.${tableClass}`);

    // Loop through each table and attach click handlers to sortable headers
    tables.forEach((table) => {
        // Check if arrows should be shown (default true, can disable with data-show-sort-arrows="false")
        const showArrows = table.getAttribute("data-show-sort-arrows") !== "false";

        // Initialize sort state for this table to track which column and direction we're sorting by
        const sortState = {
            columnIndex: null,
            direction: "asc",
        };

        // Attach click handlers to sortable header cells
        const headers = table.querySelectorAll("thead th");
        headers.forEach((header, columnIndex) => {
            // Check if header has a sortable class and set sortClass to the first matching sortable class
            const sortClass = Array.from(header.classList).find((className) =>
                className.startsWith("sortable-"),
            );
            // If header has a sortable class, make it clickable and attach sort handler
            if (sortClass) {
                header.style.cursor = "pointer";
                header.style.whiteSpace = "nowrap";
                header.addEventListener("click", () => {
                    sortTableByColumn(table, columnIndex, sortState, sortClass, showArrows);
                });
            }
        });
    });
}

/**
 * Sort table by column
 *
 * Sorts all rows in a table by the clicked column, toggling between ascending and descending order when the same column is clicked again, and uses the appropriate comparison method based on whether the data is a number, date, or string.
 *
 * @param {HTMLTableElement} table - The table element to sort.
 * @param {number} columnIndex - The index of the column to sort by (0-based).
 * @param {Object} sortState - An object tracking the current sort state with columnIndex and direction properties.
 * @param {string} sortClass - The sortable class from the header (e.g., 'sortable-number') that determines the sort type.
 * @param {boolean} showArrows - Whether to display sort direction arrows (default: true).
 * @returns {void}
 */
function sortTableByColumn(table, columnIndex, sortState, sortClass, showArrows = true) {
    // Extract all data rows from the table (skip header)
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    // Determine sort type (number, date, or string) based on the sortable class
    const sortType = getSortType(sortClass);
    // Define modifier to reverse sort order for descending
    const directionModifier = { asc: 1, desc: -1 };

    // Toggle direction if clicking same column, reset to ascending if clicking different column
    if (sortState.columnIndex === columnIndex) {
        sortState.direction = sortState.direction === "asc" ? "desc" : "asc";
    } else {
        sortState.columnIndex = columnIndex;
        sortState.direction = "asc";
    }

    // Sort rows using comparison function based on sort type
    rows.sort((a, b) => {
        const aCell = a.cells[columnIndex];
        const bCell = b.cells[columnIndex];
        // Use data-sort-value attribute if available (pre-formatted for sorting), otherwise use display text
        let aValue = aCell.getAttribute("data-sort-value") || aCell.innerText.trim();
        let bValue = bCell.getAttribute("data-sort-value") || bCell.innerText.trim();
        let comparison = 0;

        // Perform type-specific comparison
        if (sortType === "number") {
            // Convert to float and strip non-numeric characters for numeric comparison
            comparison = parseFloat(aValue.replace(/[^\d.-]/g, "")) - parseFloat(bValue.replace(/[^\d.-]/g, ""));
        } else if (sortType === "date") {
            // Parse as dates and compare timestamps
            comparison = new Date(aValue) - new Date(bValue);
        } else {
            // Case insensitive string comparison
            comparison = aValue.localeCompare(bValue, undefined, {
                sensitivity: "base",
            });
        }

        return comparison * directionModifier[sortState.direction];
    });

    // Reattach sorted rows to table in new order
    const tbody = table.querySelector("tbody");
    rows.forEach((row) => tbody.appendChild(row));

    // Update visual sort direction indicators (arrows) on the headers if enabled
    if (showArrows) {
        updateSortArrows(table, columnIndex, sortState.direction);
    }
}


/**
 * Get sort type
 *
 * Determines which type of sorting to use by examining the sortable class name and returning the corresponding sort type.
 *
 * @param {string} sortClass - The sortable class from the table header (e.g., 'sortable-number', 'sortable-date').
 *
 * @returns {string} The sort type: 'number', 'date', or 'string'.
 */
function getSortType(sortClass) {
    if (sortClass === "sortable-number") return "number";
    if (sortClass === "sortable-date") return "date";
    return "string"; // default
}


/**
 * Update sort arrows
 *
 * Removes any existing sort direction arrows from all table headers and adds an arrow to the currently sorted column to show whether it's sorted ascending or descending.
 *
 * @param {HTMLTableElement} table - The table element containing the headers to update.
 * @param {number} activeColumnIndex - The index of the column currently being sorted (0-based).
 * @param {string} direction - The current sort direction: 'asc' for ascending or 'desc' for descending.
 *
 * @returns {void}
 */
function updateSortArrows(table, activeColumnIndex, direction) {
    const headers = table.querySelectorAll("thead th");
    headers.forEach((header, index) => {
        // Remove all arrows
        const arrow = header.querySelector(".sort-arrow");
        if (arrow) {
            arrow.remove();
        }

        // Add arrow to active column
        if (index === activeColumnIndex) {
            const arrowSpan = document.createElement("span");
            arrowSpan.className = "sort-arrow";
            arrowSpan.style.marginLeft = "6px";
            arrowSpan.style.fontSize = "10px";
            arrowSpan.style.transform = "translateY(-1px)";
            arrowSpan.style.display = "inline-block";
            arrowSpan.textContent = direction === "asc" ? " ▲" : " ▼";
            header.appendChild(arrowSpan);
        }
    });
}
