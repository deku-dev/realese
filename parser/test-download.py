# -*- coding: cp1251 -*-
import os
import asyncio
import aiohttp  # pip install aiohttp
import aiofiles  # pip install aiofiles
import common

urlList = common.getCategory("https://s8.torents-igruha.org/")

REPORTS_FOLDER = "reports"
FILES_PATH = os.path.join(REPORTS_FOLDER, "files")


def download_files_from_report(urls):
    os.makedirs(FILES_PATH, exist_ok=True)
    sema = asyncio.BoundedSemaphore(5)

    async def fetch_file(url):
        fname = url.split("/")[-1]
        async with sema, aiohttp.ClientSession() as session:
            async with session.get(url) as resp:
                assert resp.status == 200
                data = await resp.read()

        async with aiofiles.open(
            os.path.join(FILES_PATH, fname), "w", encoding='cp1251'
        ) as outfile:
            await outfile.write(data.decode(encoding = 'utf-8', errors="ignore"))

    loop = asyncio.get_event_loop()
    tasks = [loop.create_task(fetch_file(url)) for url in urls]
    loop.run_until_complete(asyncio.wait(tasks))
    

download_files_from_report(["https://s8.torents-igruha.org/778-world-of-tanks.html", "https://s8.torents-igruha.org/778-world-of-tanks.html"])