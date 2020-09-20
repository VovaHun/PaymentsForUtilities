package dev.akat.smartmeter.network

import com.google.gson.JsonDeserializationContext
import com.google.gson.JsonDeserializer
import com.google.gson.JsonElement
import java.lang.reflect.Type
import java.text.ParseException
import java.text.SimpleDateFormat
import java.util.*

class ApiDateDeserializer(datePattern: String) : JsonDeserializer<Date> {
    private val formatter = SimpleDateFormat(datePattern, Locale.getDefault())

    override fun deserialize(
        json: JsonElement,
        typeOfT: Type,
        context: JsonDeserializationContext
    ): Date? {
        if (json.asString.isEmpty()) {
            return null
        }

        return try {
            formatter.parse(json.asString)
        } catch (e: ParseException) {
            null
        }
    }
}