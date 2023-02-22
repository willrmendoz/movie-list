# movie-list
## Summary
- PHP page and backend architecture for creating a list of movies and listing who is interested in them
- small personal project I undertook after learning basics of PHP to test myself

## Notes
- tested and run using XAMPP web server development package currently, which is inaccessible outside of host network, so no security measures are in place in current build

## Known Issues
- if the add movie form is invalid, the page returns, but the check box buttons do not persist, meaning user must enter them again
- clicking return to list while editing a movie deletes the movie from the list
- addMovie.php: ValidateForm() is always called when the page loads, even when navigating to it for the first time

## Future Work
* modify code to make it more abstract (i.e. get rid of hard-coded values)
* use a database instead of a file; this could allow user to create custom list of who is interested
* test on a system other than XAMPP in effort to allow access to project outside of host network
