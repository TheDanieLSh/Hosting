import { serve } from "https://deno.land/std/http/server.ts";
import { extname } from "https://deno.land/std/path/mod.ts";
import { lookup } from "https://deno.land/x/media_types/mod.ts";

const IP = Deno.networkInterfaces()
    .flat()
    .find(iface => iface.family === 'IPv4' && !iface.internal)?.address;

const PORT = 3000;

const handler = async (req) => {
    const url = new URL(req.url);
    const pathname = url.pathname === '/' ? 'index.html' : url.pathname;
    const filePath = `./dist${pathname}`;

    try {
        const fileContent = await Deno.readFile(filePath);
        const contentType = lookup(extname(filePath)) || "text/plain";

        return new Response(fileContent, {
            status: 200,
            headers: { "Content-Type": contentType },
        });
    } catch (err) {
        if (err instanceof Deno.errors.NotFound) {
            try {
                const indexContent = await Deno.readFile("./dist/index.html");
                return new Response(indexContent, {
                    status: 200,
                    headers: { "Content-Type": "text/html" },
                });
            } catch (err) {
                console.log(err);
                return new Response("Internal server error", { status: 500 });
            }

        } else {
            console.log(err);
            return new Response("Internal server error", { status: 500 });
        }
    }
};

await serve(handler, { hostname: IP, port: PORT });
