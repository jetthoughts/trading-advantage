/*
 * pages_calendar.js
 *
 * Demo JavaScript used on dashboard and calendar-page.
 */

"use strict";

$(document).ready(function(){

	//===== Calendar =====//
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();

	var h = {};

	if ($('#calendar').width() <= 400) {
		h = {
			left: 'title',
			center: '',
			right: 'prev,next'
		};
	} else {
		h = {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		};
	}

	$('#calendar').fullCalendar({
		disableDragging: false,
		header: h,
		editable: true,
		events: [{
				title: 'Trading Class',
				start: new Date(y, m, 1),
				backgroundColor: App.getLayoutColorCode('yellow')
			}, {
				title: 'In Person Event',
				start: new Date(y, m, d - 5),
				end: new Date(y, m, d - 2),
				backgroundColor: App.getLayoutColorCode('green')
			}, {
				title: 'Fly-in Event',
				start: new Date(y, m, d - 3, 16, 0),
				allDay: false,
				backgroundColor: App.getLayoutColorCode('red')
			}, {
				title: 'Mock Trading at CME',
				start: new Date(y, m, d + 4, 16, 0),
				allDay: false,
				backgroundColor: App.getLayoutColorCode('green')
			}, {
				title: 'Dinner Meetup',
				start: new Date(y, m, d, 10, 30),
				allDay: false,
			}
		]
	});

});