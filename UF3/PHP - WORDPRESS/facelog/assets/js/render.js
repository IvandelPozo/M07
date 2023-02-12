// This scripts awaits for a facelog_data variable

let facelog_intervals = [];
let facelog_indexes = [];
let facelog_images = {};
let facelog_dates = {};

    for (const user in facelog_data) {
        facelog_images[user] = []
        facelog_indexes[user] = 0
        facelog_dates[user] = []

        for (const row in facelog_data[user]) {
            //Date
            facelog_dates[user].push(facelog_data[user][row].date)
            // Image
            let image = new Image()
            image.src = facelog_data[user][row].img
            image.onload = function () {
                facelog_images[user].push(image)
            }
        }

        let interval = setInterval(()=>{
            let canvas = document.getElementById("facelog_canvas_" + user)
            let info = document.getElementById("facelog_info_" + user)

            let context = canvas.getContext('2d');
            let image = facelog_images[user][facelog_indexes[user]]
            context.globalCompositeOperation = "source-over"
            context.drawImage(image, 0, 0, 300, 500)
            info.innerHTML = facelog_dates[user][facelog_indexes[user]]

            facelog_indexes[user] ++;
            if(facelog_indexes[user] >= facelog_images[user].length)
                facelog_indexes[user] = 0
        }, velocitatGaleria)

        facelog_intervals.push(interval)
    }