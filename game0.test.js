import { jest, describe, it, expect, beforeEach, afterAll } from '@jest/globals'
import { game, Booster, restartLevel, endgameDisplayLayout } from './gameNew.js'
import {main} from './index.js'

// document.body.innerHTML = `<canvas id='pane'></canvas><input id="inputtext">
// <button id = "continuebtn"></button>
// <button id = "restartbtn"></button>
// <button id = "homebtn"></button>`

describe("Booster", () => {
    
    // jest.spyOn(Booster,'init')
    describe("levels", () => {
        const booster = new Booster('slowMo',1,1,1,['1'],[],[],[])
        it("should initialize values with init() function", () => {
            expect(booster.wordID).toBe('1')
        })

        it("should be called", () => {
        })
    })
})