/**
 * Sort table rows
 * Version: 1.0.0
 * @param {number} columnIndex - The index of the column to sort by.
 * @param {string} sortType - The type of sorting to apply ('number' or 'string').
 * Todo: Add support for multiple tables on the same page.
 */
export function sortTable(columnIndex, sortType) {
    const table = document.querySelector('#table-sortable');
    const loadingIndicator = document.querySelector('#loading-indicator');
    const rowsArray = Array.from(table.querySelectorAll('tr:nth-child(n+2)')); // Skip the header row
    const dirModifier = { 'asc': 1, 'desc': -1 };
    let direction = 'asc';
    // Define a generic sort function
    const sortFunction = (a, b) => {
        const aValue = a.cells[columnIndex].innerText.trim();
        const bValue = b.cells[columnIndex].innerText.trim();
        let comparison = 0;
        if (sortType === 'number') {
            // Convert to float and strip non-numeric characters
            comparison = parseFloat(aValue.replace(/[^\d.-]/g, '')) - parseFloat(bValue.replace(/[^\d.-]/g, ''));
        } else {
            // Case insensitive comparison for strings
            comparison = aValue.localeCompare(bValue, undefined, { sensitivity: 'base' });
        }
        return comparison * dirModifier[direction];
    };
    // Initial sort
    rowsArray.sort(sortFunction);
    // If no changes are made, reverse the order
    if (!isDifferent(rowsArray)) {
        direction = direction === 'asc' ? 'desc' : 'asc';
        rowsArray.reverse(); // Simply reverse the array if already sorted in ascending
    }
    // Function to check if the newly sorted array is different from the initial
    function isDifferent(sortedRows) {
        return !sortedRows.every((row, index) => row === table.querySelectorAll('tr:nth-child(n+2)')[index]);
    }
    // Reattach rows to the table
    for (const row of rowsArray) {
        table.appendChild(row);
    }
    function displayLoading() {
        if (loadingIndicator) {
            loadingIndicator.style.display = 'flex';
        }
    }
    function hideLoading() {
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }
    }
    displayLoading();
    setTimeout(() => {
        hideLoading();
    }, 500);
}